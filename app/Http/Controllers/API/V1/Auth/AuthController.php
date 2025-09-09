<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $valedated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user=User::where('email', $request->email)->firstOrFail();
        if(!$user ){
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }
        log::info('Login successful');
        $token = $user->createToken('auth_token')->plainTextToken;
       return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
