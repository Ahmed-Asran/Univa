<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Log;

class ProfileContoller extends Controller
{
    public function update(UpdateStudentProfileRequest $request)
    {
        Log::info('update student profile');
        $user = auth('sanctum')->user();
        Log::info('Authenticated user:', [$user]);

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // update student data (only if present)
        $studentData = $request->only(['phone', 'address']);
        $studentData = array_filter($studentData, fn($v) => !is_null($v));

        if (!empty($studentData) && $user->student) {
            $user->student()->update($studentData);
        }

        // update user email (only if present)
        if ($request->filled('email')) {
            $user->update(['email' => $request->email]);
        }

        Log::info('student updated successfully');
        return new UserResource($user->fresh('student'));
    }
}
