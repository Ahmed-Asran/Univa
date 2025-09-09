<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PasswardResetService;
use Illuminate\Support\Facades\Log;

class PasswardResetController extends Controller

{
    protected $PasswardResetService;
    public function __construct(PasswardResetService $PasswardResetService)
    {
        log::info('PasswardResetController');
        $this->PasswardResetService = $PasswardResetService;
    }

    public function forget( Request $request)
    { 
        $email=$request['email'];
         $token =$this->PasswardResetService->forgetpass($email);
        return response()->json(['success' => 'Email sent successfully.','token'=>$token], 200);
    }
    public function reset( Request $request)
    { 
        $email=$request->input('email');
        $token=$request->input('token');
        $password=$request->input('password');
        $this->PasswardResetService->resetpass($token,$password,$email);
        return response()->json(['success' => 'Password reset successfully.'], 200);
    }
}
