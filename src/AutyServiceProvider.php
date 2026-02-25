<?php

namespace Auty;

use Auty\Http\Middleware\AdminAuthenticate;
use Auty\Http\Middleware\OtpVerified;
use Auty\Models\Admin;
use Auty\Services\OtpService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AutyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/auty.php', 'auty');
        $this->app->singleton(OtpService::class);
    }

    public function boot(): void
    {
        $this->registerGuard();
        $this->registerMiddleware();
        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'auty');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/auty.php' => config_path('auty.php'),
        ], 'auty-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/auty'),
        ], 'auty-views');
    }

    protected function registerGuard(): void
    {
        config([
            'auth.guards.admin' => [
                'driver'   => 'session',
                'provider' => 'admins',
            ],
            'auth.providers.admins' => [
                'driver' => 'eloquent',
                'model'  => Admin::class,
            ],
            'auth.passwords.admins' => [
                'provider' => 'admins',
                'table'    => 'admin_password_reset_tokens',
                'expire'   => 60,
                'throttle' => 60,
            ],
        ]);
    }

    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('auty.auth', AdminAuthenticate::class);
        $this->app['router']->aliasMiddleware('auty.otp',  OtpVerified::class);
    }

    protected function registerRoutes(): void
    {
        Route::group([
            'prefix'     => config('auty.prefix', 'admin'),
            'middleware' => ['web'],
            'as'         => 'auty.',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }
}
