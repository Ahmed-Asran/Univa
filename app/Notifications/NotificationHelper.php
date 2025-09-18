<?php

namespace App\Notifications;

use App\Notifications\Channels\NotificationChannel;

class NotificationHelper
{
    protected static $channels = [];

    public static function registerChannel($name, NotificationChannel $channel)
    {
        self::$channels[$name] = $channel;
    }

    public static function notify($users, $subject, $message, $channels = ['email'])
    {
        foreach ($users as $user) {
            foreach ($channels as $channel) {
                if (isset(self::$channels[$channel])) {
                    self::$channels[$channel]->send($user, $subject, $message);
                }
            }
        }
    }
}
