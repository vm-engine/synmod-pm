<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Support\ServiceProvider;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;

class PmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateOldDataCommand::class,
            ]);
        }
    }
}
