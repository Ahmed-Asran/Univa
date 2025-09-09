<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        log::info('UserController created');
        $this->userService = $userService;

        // Apply middleware for Admin access
        //$this->middleware('role:admin'); 
    }

    public function store(CreateUserRequest $request)
    {
        log::info('Storing a new user');
        $data = $request->validated();
        try{
            log::info('Creating a new user' );
        $user = $this->userService->createUser($data);
         
        return new UserResource($user);
        }catch(\Exception $e){
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        
    }
}
}
