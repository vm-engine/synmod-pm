<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;

class PmServiceProvider extends ServiceProvider
{
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
            ]);
        }
    }

    public function registerComponents(): void
    {
        Livewire::component('pm::master.division', Division::class);
        Livewire::component('pm::master.client-category', ClientCategory::class);
    }
}
