<?php

namespace App\Notifications\Channels;

interface NotificationChannel
{
    public function send($user, $subject, $message);
}