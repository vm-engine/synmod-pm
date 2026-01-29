<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use VmEngine\Pm\Console\Commands\ImportProjectsCommand;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Console\Commands\MigrateProjectUploadsCommand;
use VmEngine\Pm\Console\Commands\ResetIndexCommand;
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
        if ($this->app->runningInConsole()) {
            // Register commands
            $this->commands([
                ImportProjectsCommand::class,
                MigrateOldDataCommand::class,
                SyncClientUserStatusCommand::class,
                ResetIndexCommand::class,
                MigrateProjectUploadsCommand::class,
            ]);

            $schedule = $this->app->make(Schedule::class);
            $schedule->command('mod-pm:reset-index')->yearly();
            $schedule->command('queue:work --stop-when-empty --max-time=270 --tries=3')
                ->everyMinute()
                ->withoutOverlapping()
                ->runInBackground();
        }
    }
}
