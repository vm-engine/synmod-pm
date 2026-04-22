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

    public array $generalConfigs = [
        'vat_percentage'          => 11.0,
        'include_vat_default'     => true,
        'dpp_numerator'           => 11,
        'dpp_denominator'         => 12,
        'payment_due_days'        => 14,
        'default_issuer_id'       => null,
        'default_bank_account_id' => null,
        'default_signature_id'    => null,
    ];

    public function mount(): void
    {
        // Pre-load document configs
        foreach (Documents::cases() as $document) {
            $key = $document->code(true) ?: $document->name;
            $this->docConfigs['format'][$key] = dbconf("pm.documents.{$key}.number_format", '');
        }

        $this->generalConfigs = [
            'vat_percentage'          => (float) dbconf('pm.invoice.vat_percentage', 11.0),
            'include_vat_default'     => (bool)  dbconf('pm.invoice.include_vat_default', true),
            'dpp_numerator'           => (int)   dbconf('pm.invoice.dpp_numerator', 11),
            'dpp_denominator'         => (int)   dbconf('pm.invoice.dpp_denominator', 12),
            'payment_due_days'        => (int)   dbconf('pm.invoice.payment_due_days', 14),
            'default_issuer_id'       => dbconf('pm.invoice.default_issuer_id', null),
            'default_bank_account_id' => dbconf('pm.invoice.default_bank_account_id', null),
            'default_signature_id'    => dbconf('pm.invoice.default_signature_id', null),
        ];

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

    public function saveGeneral(): void
    {
        if (! Auth::user()->can('pm.config.update')) {
            session()->flash('error', __('synapse::messages.no_permission'));

            return;
        }

        $this->validate([
            'generalConfigs.vat_percentage'          => ['required', 'numeric', 'min:0', 'max:100'],
            'generalConfigs.include_vat_default'     => ['boolean'],
            'generalConfigs.dpp_numerator'           => ['required', 'integer', 'min:1'],
            'generalConfigs.dpp_denominator'         => ['required', 'integer', 'min:1'],
            'generalConfigs.payment_due_days'        => ['required', 'integer', 'min:0'],
            'generalConfigs.default_issuer_id'       => ['nullable', 'integer', 'exists:document_issuers,id'],
            'generalConfigs.default_bank_account_id' => ['nullable', 'integer', 'exists:bank_accounts,id'],
            'generalConfigs.default_signature_id'    => ['nullable', 'integer', 'exists:signatures,id'],
        ]);

        foreach ($this->generalConfigs as $key => $value) {
            dbconf(["pm.invoice.{$key}" => $value]);
        }

        SynAuth::logActivity(
            Auth::id(),
            'pm.config.update',
            'User '.Auth::user()->name.' updated invoice configuration',
            'config',
            'pm',
        );

        $this->dispatch('notify',
            variant: 'success',
            title: __('pm::config.general.messages.save_success_title'),
            message: __('pm::config.general.messages.save_success_message'),
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
    public function issuerOptions(): array
    {
        return \VmEngine\Pm\Models\DocumentIssuer::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn ($i) => ['value' => $i->id, 'label' => $i->name])
            ->toArray();
    }

    #[Computed()]
    public function bankAccountOptions(): array
    {
        return \VmEngine\Pm\Models\BankAccount::query()
            ->select('id', 'name', 'bank_name')
            ->orderBy('name')
            ->get()
            ->map(fn ($b) => ['value' => $b->id, 'label' => $b->name.' — '.$b->bank_name])
            ->toArray();
    }

    #[Computed()]
    public function signatureOptions(): array
    {
        return \VmEngine\Pm\Models\Signature::query()
            ->select('id', 'name', 'position')
            ->orderBy('name')
            ->get()
            ->map(fn ($s) => ['value' => $s->id, 'label' => $s->name.' — '.$s->position])
            ->toArray();
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
