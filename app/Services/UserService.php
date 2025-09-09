<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\User\UserResource;
class UserService
{
    protected function generateUserId($type)
    {
        if ($type === 'student') {
            $year = date('Y');
            $last = Student::where('student_id', 'like', $year . '%')
                ->orderBy('student_id', 'desc')
                ->first();

            $lastNumber = $last ? (int)substr($last->student_id, 4) : 0;

            return $year . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

     if ($type === 'faculty') {
        // Faculty IDs start from 100000 (6-digit range: 100000-199999)
        $last = Faculty::where('faculty_id', '>=', 100000)
            ->where('faculty_id', '<', 200000)
            ->orderBy('faculty_id', 'desc')
            ->first();
        
        $lastNumber = $last ? (int)$last->faculty_id : 99999;
        return (string)($lastNumber + 1);
    }

    if ($type === 'admin') {
        // Admin IDs start from 200000 (6-digit range: 200000-299999)
        $last = User::whereHas('roles', function ($q) {
            $q->where('role_name', 'admin');
        })
            ->where('user_id', '>=', 200000)
            ->where('user_id', '<', 300000)
            ->orderBy('user_id', 'desc')
            ->first();

        $lastNumber = $last ? (int)$last->user_id : 199999;
        return (string)($lastNumber + 1);
    }
        return null;
    }

    public function createUser($data)
    {
        $userExists = User::where('email', $data['email'],)->first();
        if ($userExists) {
            Log::warning("User with email {$data['email']} already exists.");
            return $userExists;
        }
        DB::beginTransaction();

        try {
            $role = $data['role'];
            Log::info("Generating username for role: {$role}");

            $username = $this->generateUserId($role);
            Log::info("Generated username: {$username}");

            // Create User
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'password'      => bcrypt($data['password']),
                'username'      => $username,
                'password_hash' => bcrypt($data['password']),
                'is_active'     => $data['is_active'] ?? true,
                'is_deleted'    => false,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            Log::info("User created with ID: {$user->user_id}");

            // Handle student
            if ($role === 'student') {
                Log::info("Creating student profile for user_id: {$user->user_id}");
                
                $studentData = [
                    'student_id'    => $username, // Make sure this is the generated ID
                    'user_id'       => $user->user_id,
                    'phone'         => $data['phone'] ?? null,
                    'address'       => $data['address'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'level'         => $data['level'] ?? 'First',
                    'total_credits' => $data['total_credits'] ?? 0,
                    'current_gpa'   => $data['gpa'] ?? 0.0,
                    'is_deleted'    => false,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                Log::info("Creating student with data: ", $studentData);
                
                $student = Student::create($studentData);
                Log::info("Student created successfully with student_id: {$student->student_id}");
            }

            // Handle faculty
            elseif ($role === 'faculty') {
                Log::info("Creating faculty profile for user_id: {$user->user_id}");
                $depId = null;

                if (!empty($data['department'])) {
                    $department = Department::where('department_name', $data['department'])->first();
                    $depId = $department ? $department->department_id : null;

                    if (!$depId) {
                        Log::warning("Department '{$data['department']}' not found");
                    }
                } else {
                    Log::info("No department provided in request ");
                    $department = Department::where('department_name','general')->first();
                    $depId = $department ? $department->department_id : null;
                }
                $facultyData = [
                    'faculty_id'    => $username,
                    'user_id'       => $user->user_id,
                    'position'      => $data['position'] ?? null,
                    'department_id' => $depId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $faculty = Faculty::create($facultyData);
                Log::info("Faculty created successfully with faculty_id: {$faculty->faculty_id}");
            }

            // Attach Role
            $roleModel = Role::where('role_name', $role)->first();
            Log::info("Looking for role: {$role}");
            
            if ($roleModel) {
                Log::info("Role found: {$roleModel->role_name} with ID: {$roleModel->role_id}");

                $userRole = UserRole::create([
                    'user_id'     => $user->user_id,
                    'role_id'     => $roleModel->role_id,
                    'assigned_at' => now(),
                ]);

                Log::info("UserRole created successfully: User {$user->user_id} assigned role {$roleModel->role_id}");
            } else {
                Log::error("Role '{$role}' not found in roles table!");
                throw new \Exception("Role '{$role}' not found");
            }

            DB::commit();
            Log::info("Transaction committed successfully");

            // Return user with relationships loaded
            $userWithRelations = $user->load('student', 'faculty', 'roles');
            Log::info("User created successfully with all relationships loaded");
            
            return new UserResource($user->load('student'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}