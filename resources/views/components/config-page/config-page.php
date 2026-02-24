<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    public string $activeTab = 'doc_no_format';

    public array $docConfigs = [
        'format' => [],
        'next_c_index' => [],
        'next_nc_index' => [],
    ];

    public function mount(): void
    {
        // Pre-load document configs
        foreach (Documents::cases() as $document) {
            $key = $document->code(true) ?: $document->name;
            $this->docConfigs['format'][$key] = dbconf("pm.documents.{$key}.number_format", '');
        }

        synav()->setActiveMenu('pm.config');
    }

    public function title(): string
    {
        return __('pm::menu.be.config.index');
    }

    public function save(): void
    {
        if (! Auth::user()->can('pm.config.update')) {
            session()->flash('error', __('synapse::messages.no_permission'));

            return;
        }

        foreach (Documents::cases() as $document) {
            $key = $document->code(true) ?: $document->name;
            dbconf(["pm.documents.{$key}.number_format" => $this->docConfigs['format'][$key]]);
        }

        SynAuth::logActivity(
            Auth::id(),
            'pm.config.update',
            'User '.Auth::user()->name.' updated configuration',
            'config',
            'pm',
        );

        $this->dispatch('notify',
            variant: 'success',
            title: __('pm::config.doc_no_format.messages.save_success_title'),
            message: __('pm::config.doc_no_format.messages.save_success_message'),
        );
    }

    #[Computed()]
    public function configs(): array
    {
        $configs = [];
        foreach (Documents::cases() as $document) {
            $key = $document->code(true) ?: $document->name;
            $configs[] = [
                'key' => $key,
                'label' => $document->label().' (Code: '.$document->code().')',
                'format' => dbconf("pm.documents.{$document->code(true)}.number_format", ''),
            ];
        }

        return $configs;
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.config.index'),
            icon: 'fa-solid fa-gear',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
