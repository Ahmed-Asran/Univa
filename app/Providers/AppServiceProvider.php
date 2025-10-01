<?php

namespace App\Providers;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\ServiceProvider;
use App\Notifications\NotificationHelper;
use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\InAppChannel;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Throwable;
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
           // resolve the exception handler
        $handler = $this->app->make(Handler::class);

        // Model not found
        $handler->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json(['error' => 'Resource not found'], 404);
        });

        // Invalid arguments
        $handler->renderable(function (InvalidArgumentException $e, $request) {
            return response()->json(['error' => $e->getMessage()], 400);
        });

        // Catch-all
        $handler->renderable(function (Throwable $e, $request) {
            return response()->json(['error' => $e->getMessage()], 500);
        });
}
}
