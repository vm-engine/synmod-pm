<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Console\Commands\ResetIndex;
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
                ResetIndex::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('mod-pm:reset-index')->yearly();
            });
        }
    }
}
