<?php

namespace App\Notifications\Channels;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMail;

class EmailChannel implements NotificationChannel
{
    public function send($user, $subject, $message)
    {
        Mail::to($user->email)->send(new GenericMail($subject, $message));
    }
}