<?php

namespace App\Providers;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\ServiceProvider;
use App\Notifications\NotificationHelper;
use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\InAppChannel;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
         $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
          NotificationHelper::registerChannel('email', new EmailChannel());
          //NotificationHelper::registerChannel('in_app', new InAppChannel());
    }
}
