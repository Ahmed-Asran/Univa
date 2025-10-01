<?php
namespace App\Services;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswardResetService
{
    public function forgetPass($email)
    {
        $user = User::where('email', $email)->first();
        log::info($user);
        if (!$user) {
            return "wrong email";
        }

        $token = Str::random(60);

        PasswordResetToken::create([
            'token'   => $token,
            'user_id' => $user->user_id,
            'created_at' => now(),
            'expires_at'=> now()->addMinutes(30),
            'used'=>0,
        ]);
        log::info($token);

        // Mail::send('emails.reset', ['token' => $token], function ($message) use ($user) {
        //     $message->to($user->email)->subject('Reset Password');
        // });
            Mail::raw("Use this token to reset your password: $token", function ($message) use ($user) {
    $message->to($user->email)
            ->subject('Reset Password');
});
        

        return [$token];
    }

    public function resetPass($token, $password, $email)
    {
        $tokenRow = PasswordResetToken::where('token', $token)->first();
        if (!$tokenRow|| $tokenRow->used|| $tokenRow->expires_at->isPast() ){
            return "invalid token";
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return "invalid email";
        }

        $user->password_hash = Hash::make($password);
        $user->save();

        $tokenRow->delete();

        return "password reset successfully";
    }
}
