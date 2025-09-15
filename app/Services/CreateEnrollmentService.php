<?php
namespace App\Services;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\AcademicTerm;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;  
class CreateEnrollmentService
{
    public function showAvailableEnrollments($studentId)
    {      
        try {
            // 1. Current term
            $term = AcademicTerm::where('is_current', 1)->first();
            if (!$term) {
                throw new Exception('No active term found.');
            }

            // 2. All course sections for this term (with course + prerequisites)
            $sections = CourseSection::with('course.course_prerequisites')
                ->where('term_id', $term->term_id)
                ->get();
            log::info('Total sections in current term: ' . $sections->count());
            // 3. Courses student already passed
            $completedCourses = Enrollment::where('student_id', $studentId)
                ->where('status', 'Completed') 
                ->with('course_section.course')
                ->get()
                ->map(fn($enrollment) => $enrollment->course_section?->course?->course_id)
                ->filter()
                ->unique()
                ->toArray();
            log::info('Completed courses: ' . implode(',', $completedCourses));
            // 4. Filter eligible course sections
            $eligible = [];
            log::info('Filtering eligible sections...', $sections->toArray());
            foreach ($sections as $section) {
                $course = $section->course;
                log::info("Section {$section->section_id} course: " . ($course ? $course->course_id : 'N/A'));
                // skip invalid section
                if (!$course) {
                    continue;
                }

                $prereqs = $course->course_prerequisites->pluck('prerequisite_course_id')->toArray();
                log::info("Section {$section->section_id} prerequisites: " . implode(',', $prereqs));
                // if no prerequisites OR all prerequisites are satisfied
                if (empty($prereqs) || !array_diff($prereqs, $completedCourses)) {
                    $eligible[] = $section;
                }
            }
            log::info('Eligible sections found: ' . count($eligible));

            return collect($eligible);
        } catch (Exception $e) {
            Log::error('Enrollment error: ' . $e->getMessage());
            throw new Exception('Failed to fetch available enrollments: ' . $e->getMessage());
        }
    }
        
    
    public function createEnrollments(array $enrolments)
    {
        return DB::transaction(function () use ($enrolments) {
            $created = [];

            foreach ($enrolments as $enrol) {
                $student = Student::where('student_id', $enrol['student_id'])->firstOrFail();
                $section = CourseSection::where('section_id', $enrol['section_id'])->firstOrFail();

                //  Check if already enrolled
                $exists = Enrollment::where('student_id', $student->student_id)
                    ->where('section_id', $section->section_id)
                    ->first();

                if ($exists) {
                    throw new Exception("Student {$student->student_id} is already enrolled in section {$section->section_id}");
                }

                // Check prerequisites
                $eligibleSections = $this->showAvailableEnrollments($student->student_id);

                if (!$eligibleSections->contains('section_id', $section->section_id)) {
                    throw new Exception("Student {$student->student_id} is not eligible to enroll in section {$section->section_id}");
                }
                

                // ✅ Create enrollment
                $created = Enrollment::create([
                    'student_id'       => $student->student_id,
                    'section_id'       => $section->section_id,
                    'enrollment_date'  => $enrol['enrollment_date']?? now(),
                    'status'           => $enrol['status'] ?? 'Enrolled',
                    'final_grade'      => $enrol['final_grade'] ?? null,
                    'result'           => $enrol['result'] ?? null,
                ]);
                $section->increment('current_enrollment'); // Increment enrolled count
            }

            return $created;
        });
    }
}