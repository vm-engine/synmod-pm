<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use VmEngine\Pm\Console\Commands\ImportProjectsCommand;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Console\Commands\MigrateProjectUploadsCommand;
use VmEngine\Pm\Console\Commands\ResetIndexCommand;
use VmEngine\Pm\Console\Commands\SyncClientUserStatusCommand;
use VmEngine\Pm\Http\Middleware\ApiLogger;
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
        /** @var Router $router */
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('pm.api.logger', ApiLogger::class);

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
            $schedule->command('queue:work --once --max-time=270 --tries=3')
                ->everyMinute()
                ->withoutOverlapping(5)
                ->runInBackground();
        }
    }
}
