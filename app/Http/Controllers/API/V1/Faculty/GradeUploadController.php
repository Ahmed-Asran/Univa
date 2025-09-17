<?php

namespace App\Http\Controllers\API\V1\Faculty;

use App\Http\Controllers\Controller;
use App\Services\GradeUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class GradeUploadController extends Controller
{
    protected $gradeUploadService;
    public function __construct(GradeUploadService $gradeUploadService)
    {
        $this->gradeUploadService = $gradeUploadService;
    }
     public function upload(Request $request, $assignmentId)
    {
        log::info('upload grades request received', ['request' => $request->all()]);
        // Validate file input
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048', 
        ]);
        log::info('upload grades request validated', ['request' => $request->all()]);

        try {
            $result = $this->gradeUploadService->uploadGrades($request->file('file'), $assignmentId);
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
