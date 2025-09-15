<?php

namespace App\Http\Controllers\API\V1\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Students\CraeteEnrollmentRequest;
use App\Services\CreateEnrollmentService;
use Illuminate\Support\Facades\Log;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateEnrollmentController extends Controller
{
    protected $enrollmentService;
    public function __construct(CreateEnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }
    public function index(Request $request)
    {
        $studentId = $request->user()->student->student_id; // Assuming the student ID is the authenticated user's ID
        log::info('Fetching available enrollments...');
       $availableEnrollments = $this->enrollmentService->showAvailableEnrollments($studentId);
        return response()->json([
            'message' => 'Available enrollments fetched successfully.',
            'data' => $availableEnrollments
        ], 200);
    }
    public function store(CraeteEnrollmentRequest $request)
    {
        $authStudentId = $request->user()->student->student_id;
        $enrollmentsData = $request->input('enrolments');

        // Check each enrollment request belongs to the authenticated student
        foreach ($enrollmentsData as $enrol) {
            if ($enrol['student_id'] != $authStudentId) {
                return response()->json([
                    'message' => 'You are not authorized to enroll other students.'
                ], 403);
            }
        }
        try {
            $data = $request->validated();
            $enrollments = $this->enrollmentService->createEnrollments($data['enrolments']);

            return response()->json([
                'success' => true,
                'data'    => $enrollments,
                'message' => 'Enrollments created successfully',
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

}
