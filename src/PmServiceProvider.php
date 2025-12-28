<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Support\ServiceProvider;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Console\Commands\SyncClientUserStatusCommand;
use VmEngine\Synapse\Traits\AutoRegistersComponents;

class PmServiceProvider extends ServiceProvider
{
    use AutoRegistersComponents;

    public function register(): void
    {
        $this->registerComponents();
    }

    public function boot(): void
    {
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateOldDataCommand::class,
                SyncClientUserStatusCommand::class,
            ]);
        }
    }
}
