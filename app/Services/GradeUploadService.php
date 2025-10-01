<?php 
namespace App\Services;
use App\Models\Grade;
use App\Models\Assignment;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class GradeUploadService{
    public function uploadAssignmentGrades($file, $assignmentId)
    {
        // Ensure assignment exists
        $assignment = Assignment::find($assignmentId);
        if (!$assignment) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Assignment not found',
            ], 404));
        }

        // Read the file (assuming CSV for now)
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('trim', $rows[0]);
        unset($rows[0]);
        log::info('checking header of the file ', ['header' => $header]);
        // Expected columns
        if ($header !== ['assignment_id', 'student_id', 'grade']) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Invalid file format. Expected columns: assignment_id, student_id, grade',
            ], 422));
        }
        log::info('header checked successfully');
        log::info('checking data of the file ', ['data' => $rows]);
        // Validate all rows
        $grades = [];
        foreach ($rows as $row) {
            [$rowAssignmentId, $studentId, $pointsEarned] = $row;
            log::info(' checking row ', ['row' => $row]);
            // Check assignment ID consistency
            if ($rowAssignmentId != $assignmentId) {
                log::error('Mismatched assignment ID in file');
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => "Mismatched assignment ID in file (expected $assignmentId, found $rowAssignmentId)",
                ], 422));
            }

            // Check student exists
            $student = Student::where('student_id', $studentId)->first();
            if (!$student) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => "Student with ID $studentId not found",
                ], 422));
            }

                // Check student enrollment in the assignment’s section and current term
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('section_id', $assignment->section_id)
                    ->whereHas('course_section.academic_term', function ($query) {
                        $query->where('is_current', 1);
                    })
                    ->first();

                if (!$enrollment) {
                    throw new HttpResponseException(response()->json([
                        'success' => false,
                        'message' => "Student $studentId is not enrolled in this section for the current term",
                    ], 422));
                }

            $grades[] = [
                'assignment_id' => $assignmentId,
                'student_id'    => $studentId,
                'points_earned' => $pointsEarned,
                'graded_date'   => now(),
                'graded_by'     => auth()->user()->faculty->faculty_id,
                'enrollment_id' => $enrollment->enrollment_id
            ];
        }

        // Insert/update grades
        DB::transaction(function () use ($grades) {
            foreach ($grades as $g) {
                Grade::create(
                    [
                        'assignment_id' => $g['assignment_id'],
                        'student_id'    => $g['student_id'],
                        'points_earned' => $g['points_earned'],
                        'graded_date'   => $g['graded_date'],
                        'graded_by'     => $g['graded_by'],
                        'enrollment_id' => $g['enrollment_id']
                    ]
                );
            }
        });

        return [
            'success' => true,
            'message' => 'Grades uploaded successfully',
            'count'   => count($grades),
            'grades'  => $grades
        ];
    }
        public function uploadCourserseGrades($file, $sectionId)
    {
        // Ensure section exists
        $sectionId = CourseSection::find($sectionId);
        if (!$sectionId) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'course section not found',
            ], 404));
        }

        // Read the file (assuming CSV for now)
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_map('trim', $rows[0]);
        unset($rows[0]);
        log::info('checking header of the file ', ['header' => $header]);
        // Expected columns
        if ($header !== ['course_Section_id', 'student_id', 'grade']) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Invalid file format. Expected columns: course_Section_id, student_id, grade',
            ], 422));
        }
        log::info('header checked successfully');
        log::info('checking data of the file ', ['data' => $rows]);
        // Validate all rows
        $grades = [];
        foreach ($rows as $row) {
            [$rowcourseSectionId, $studentId, $grade] = $row;
            log::info(' checking row ', ['row' => $row]);
            // Check section ID consistency
            if ($rowcourseSectionId != $sectionId->section_id) {
                log::error('Mismatched course section  ID in file');
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => "Mismatched course section  ID in file (expected $sectionId->section_id, found $rowcourseSectionId)",
                ], 422));
            }

            // Check student exists
            $student = Student::where('student_id', $studentId)->first();
            if (!$student) {
                throw new HttpResponseException(response()->json([
                    'success' => false,
                    'message' => "Student with ID $studentId not found",
                ], 422));
            }

                // Check student enrollment in the course section and current term
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('section_id', $sectionId->section_id)
                    ->whereHas('course_section.academic_term', function ($query) {
                        $query->where('is_current', 1);
                    })
                    ->first();

                if (!$enrollment) {
                    throw new HttpResponseException(response()->json([
                        'success' => false,
                        'message' => "Student $studentId is not enrolled in this section for the current term",
                    ], 422));
                }
            [$AlphaGrade,$pointsEarned] = $this->convertToLetterAndPoints($grade);
            $grades[] = [
                'section_id' => $sectionId->section_id,
                'student_id'    => $studentId,
                'result' => $grade,
                'points_earned' => $pointsEarned,
                'AlphaGrade' => $AlphaGrade,
                'graded_date'   => now(),
                'enrollment_id' => $enrollment->enrollment_id
            ];
        }
        // Insert/update grades
        DB::transaction(function () use ($grades) {
            
            foreach ($grades as $g) {
                $enrollment = Enrollment::find($g['enrollment_id']);
                $enrollment->result = $g['result'];
                $enrollment->final_grade = $g['AlphaGrade'];
                $enrollment->status = 'Completed';
                $enrollment->save();
                $student = Student::find($g['student_id']);
                $total_credits = $student->total_credits;
                $enrollments=$student->enrollments()->where('status','Completed')->with('course_section.course')->get();
                if($enrollment->final_grade!='F'){
                    $total_credits += $enrollment->course_section->course->credit_hours;
                }
                $student->update([
                    'total_credits' => $total_credits,
                    //edit gpa here
                    'current_gpa' => $this->calcGPA($enrollments) 
                ]);
            }
                
        });

        return [
            'success' => true,
            'message' => 'Grades uploaded successfully',
            'count'   => count($grades),
            'grades'  => $grades
        ];
    }
    private function convertToLetterAndPoints($grade): array
{
    if ($grade >= 90) return ['A+', 4.0];
    if ($grade >= 85) return ['A', 3.7];
    if ($grade >= 80) return ['B+', 3.4];
    if ($grade >= 75) return ['B', 3.0];
    if ($grade >= 70) return ['C+', 2.7];
    if ($grade >= 65) return ['C', 2.4];
    if ($grade >= 60) return ['D+', 2.2];
    if ($grade >= 50) return ['D', 2.0];
    return ['F', 0.0];
}
private function calcGPA($enrollments) {
    $totalPoints = 0;
    $totalCredits = 0;

    foreach ($enrollments as $enrollment) {
        if ($enrollment->final_grade && $enrollment->course_section && $enrollment->course_section->course) {
            [$alphaGrade, $points] = $this->convertToLetterAndPoints($enrollment->result);
            $credits = $enrollment->course_section->course->credit_hours;

            $totalPoints += $points * $credits;
            $totalCredits += $credits;
        }
    }

    return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0.0;
}
public function getGradesForSection($sectionId) {
    try{
    $grades=Enrollment::with('student.user','course_section.course')
   ->where('section_id',$sectionId)
   ->get();
   return $grades;
        
    }catch(\Exception $e){
        Log::error('Error fetching grades for section: '.$e->getMessage());
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error fetching grades for section',
        ], 500));
    }

}
public function getGradesForStudent($studentId) {
    try{
    $grades=Enrollment::with('course_section.course','student.user')
   ->where('student_id',$studentId)
   ->get();
   return $grades;
        
    }catch(\Exception $e){
        Log::error('Error fetching grades for student: '.$e->getMessage());
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error fetching grades for student',
        ], 500));
    }
}

public function editGrade($enrollment, $grade)
{
    try {
        [$AlphaGrade, $pointsEarned] = $this->convertToLetterAndPoints($grade);

        // check if grade was previously null (first time grading)
        $isFirstTimeGrade = is_null($enrollment->result);
        $oldTotalCredits = $enrollment->student->total_credits;
        $oldgrade = $enrollment->final_grade;
        // update enrollment grade
        $enrollment->result = $grade;
        $enrollment->final_grade = $AlphaGrade;
        $enrollment->save();

        // get student and their completed enrollments
        $student = Student::find($enrollment->student_id);
        $enrollments = $student->enrollments()
            ->where('status', 'Completed')
            ->with('course_section.course')
            ->get();

        // update total credits only if this is the first time grade is being added
        $totalCredits = $student->total_credits;
        if ($isFirstTimeGrade) {
            $totalCredits += $enrollment->course_section->course->credit_hours;
        }
        else if($oldgrade!='F' && $AlphaGrade=='F'){
            $totalCredits -= $enrollment->course_section->course->credit_hours;
        }
        else if($oldgrade=='F' && $AlphaGrade!='F'){
            $totalCredits += $enrollment->course_section->course->credit_hours;
        }

        // update student record
        $student->update([
            'total_credits' => $totalCredits,
            'current_gpa'   => $this->calcGPA($enrollments)
        ]);

        return $enrollment;
    } catch (\Exception $e) {
        Log::error('Error editing grade: ' . $e->getMessage());
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Error editing grade',
        ], 500));
    }
}
}