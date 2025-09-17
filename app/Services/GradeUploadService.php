<?php 
namespace App\Services;
use App\Models\Grade;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class GradeUploadService{
    public function uploadGrades($file, $assignmentId)
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
}