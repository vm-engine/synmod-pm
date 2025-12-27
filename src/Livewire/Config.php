<?php

namespace VmEngine\Pm\Livewire;

use Livewire\Component;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class Config extends Component
{
    public string $activeTab = 'doc_no_format';

    public array $docConfigs = [
        'format' => [],
        'next_c_index' => [],
        'next_nc_index' => [],
    ];

    public function save(): void
    {
        // Check permission
        if (! auth()->user()->can('pm.config.update')) {
            session()->flash('error', __('synapse::messages.no_permission'));

            return;
        }

        // Save each document config
        foreach (Documents::cases() as $document) {
            $key = strtolower($document->code());
            dbconf(["pm.documents.{$key}.number_format" => $this->docConfigs['format'][$key]]);
            dbconf(["pm.documents.{$key}.next_c_index" => $this->docConfigs['next_c_index'][$key]]);
            if ($document->hasNCIndex()) {
                dbconf(["pm.documents.{$key}.next_nc_index" => $this->docConfigs['next_nc_index'][$key]]);
            }
        }

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => __('pm::config.doc_no_format.messages.save_success_title'),
            'message' => __('pm::config.doc_no_format.messages.save_success_message'),
        ]);
    }

    public function render()
    {
        $configs = [];
        foreach (Documents::cases() as $document) {
            $key = $document->code(true);
            $docConfig = [
                'key' => $key,
                'label' => $document->label().' (Code: '.$document->code().')',
                'format' => dbconf("pm.documents.{$key}.number_format", ''),
                'next_c_index' => dbconf("pm.documents.{$key}.next_c_index", 1),
            ];
            $this->docConfigs['format'][$key] = $docConfig['format'];
            $this->docConfigs['next_c_index'][$key] = $docConfig['next_c_index'];
            if ($document->hasNCIndex()) {
                $docConfig['next_nc_index'] = dbconf("pm.documents.{$key}.next_nc_index", 1);
                $this->docConfigs['next_nc_index'][$key] = $docConfig['next_nc_index'];
            }
            $configs[] = $docConfig;
        }

        synav()->setActiveMenu('pm.config');

        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.config.index'),
            icon: 'fa-solid fa-gear',
        );

        return view('pm::livewire.config', [
            'breadcrumbs' => $breadcrumbs,
            'configs' => $configs,
        ])->title(__('pm::menu.be.config.index'));
    }
}
