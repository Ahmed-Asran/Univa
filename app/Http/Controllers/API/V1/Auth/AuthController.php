<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotificationHelper;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $valedated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user=User::where('username', $request->username)->firstOrFail();
        if(!$user||!hash::check($request->password, $user->password_hash) ){
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }
        log::info('Login successful');
        $token = $user->createToken('auth_token')->plainTextToken;
         NotificationHelper::notify(
            [$user],
            "you are welcome to our platform",
            "now you can start using our platform",
            ['email']
        );
       return  ['user'=>new UserResource($user),
        'token' => $token];
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}   
