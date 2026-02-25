<?php

namespace Shahrakii\Crudly;

use Illuminate\Support\ServiceProvider;
use Shahrakii\Crudly\Console\Commands\GenerateCrudCommand;
use Shahrakii\Crudly\Console\Commands\GenerateControllerCommand;
use Shahrakii\Crudly\Console\Commands\GenerateModelCommand;

class CrudlyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/crudly.php',
            'crudly'
        );
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/crudly.php' => config_path('crudly.php'),
        ], 'crudly-config');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrudCommand::class,
                GenerateControllerCommand::class,
                GenerateModelCommand::class,
            ]);
        }
    }
}
