<?php

namespace App\Http\Controllers\API\V1\Admin;
use App\Http\Controllers\Controller;

use App\Services\CourseService;
use App\Models\Course;
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
}