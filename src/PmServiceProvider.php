<?php

declare(strict_types=1);

namespace VmEngine\Pm;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use VmEngine\Pm\Console\Commands\MigrateOldDataCommand;
use VmEngine\Pm\Console\Commands\SyncClientUserStatusCommand;
use VmEngine\Pm\Livewire\ClientForm;
use VmEngine\Pm\Livewire\ClientList;
use VmEngine\Pm\Livewire\Config;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;
use VmEngine\Pm\Livewire\Master\ProjectCategory;
use VmEngine\Pm\Livewire\Project\DocumentManager;
use VmEngine\Pm\Livewire\Project\ProjectDetail;
use VmEngine\Pm\Livewire\Project\ProjectForm;
use VmEngine\Pm\Livewire\Project\ProjectList;

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
                SyncClientUserStatusCommand::class,
            ]);
        }
    }

    public function registerComponents(): void
    {
        Livewire::component('pm::client-list', ClientList::class);
        Livewire::component('pm::client-form', ClientForm::class);
        Livewire::component('pm::project-list', ProjectList::class);
        Livewire::component('pm::project-form', ProjectForm::class);
        Livewire::component('pm::project-detail', ProjectDetail::class);
        Livewire::component('pm::document-manager', DocumentManager::class);
        Livewire::component('pm::master.division', Division::class);
        Livewire::component('pm::master.client-category', ClientCategory::class);
        Livewire::component('pm::master.project-category', ProjectCategory::class);
        Livewire::component('pm::config', Config::class);
    }
}
