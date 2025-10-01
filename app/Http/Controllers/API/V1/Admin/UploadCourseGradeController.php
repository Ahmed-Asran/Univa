<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;

use App\Services\CourseService;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\GradeUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
class UploadCourseGradeController extends Controller
{
    protected $gradeUploadService;

    public function __construct(GradeUploadService $courseService)
    {
        $this->gradeUploadService = $courseService;
    }
     public function uploadCourseGrades(Request $request, $sectionId)
    {
        log::info('upload grades request received', ['request' => $request->all()]);
        // Validate file input
        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }
        log::info('upload grades request validated', ['request' => $request->all()]);

        try {
            $result = $this->gradeUploadService->uploadCourserseGrades($request->file('file'), $sectionId);
            log::info('Grades uploaded successfully', ['result' => $result]);
            return response()->json($result, 200);

        } 
        catch (HttpResponseException $e) {
        // let Laravel return the response that the exception threw in the service  
        throw $e;
    }
        catch (\Exception $e) {
            log::error('Error uploading grades: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getGradesforSection($sectionId)
    {
        try {
            $grades = $this->gradeUploadService->getGradesForSection($sectionId);
            return response()->json([
                'success' => true,
                'data' => $grades,
            ], 200);
        } catch (HttpResponseException $e) {
            // let Laravel return the response that the exception threw in the service  
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error fetching grades: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
}
public function getGradesforStudent($studentId)
    {
        $student=request()->user()->student;
        log::info('Authenticated student', ['student' => $student]);
        if($student)
        {
            if( $student->student_id != $studentId){
                log::info('Unauthorized access attempt by student', ['authenticated_student_id' => $student->student_id, 'requested_student_id' => $studentId]);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view these grades',
            ], 403);
        }
        }
        
        try {
            $grades = $this->gradeUploadService->getGradesForStudent($studentId);
            return response()->json([
                'success' => true,
                'data' => $grades,
            ], 200);
        } catch (HttpResponseException $e) {
            // let Laravel return the response that the exception threw in the service  
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error fetching grades: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function editGrade($studentId,$sectionId)
    {
        $request=request();
        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
        ]);
        $enroll=Enrollment::where('student_id',$studentId)->with('student')->with('course_section.course')
        ->where('section_id',$sectionId)
        ->firstOrFail();
        
        try {
            $result = $this->gradeUploadService->editGrade($enroll,$request->grade);
            return response()->json($result, 200);

        } 
        catch (HttpResponseException $e) {
        // let Laravel return the response that the exception threw in the service  
        throw $e;
    }
        catch (\Exception $e) {
            log::error('Error editing grade: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
            ], 500);
        }
    }
}