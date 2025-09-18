<?php

namespace App\Notifications\Channels;

use App\Models\Notification;

class InAppChannel implements NotificationChannel
{
    public function send($user, $subject, $message)
    {
        Notification::create([
            'user_id' => $user->id,
            'title'   => $subject,
            'message' => $message,
            'status'  => 'unread'
        ]);
    }
}
