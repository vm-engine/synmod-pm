<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\SynAuth\Facades\SynAuth;

class InvoiceList extends Component
{
    use WithPagination;

    public ?int $projectId = null;

    public $q = '';

    public string $filterStatus = '';

    public string $filterYear = '';

    public string $filterIssuer = '';

    public string $filterCreator = '';

    public int $limit = 10;

    public string $sort = 'id';

    public string $sortDirection = 'desc';

    public ?Project $project = null;

    public function mount(): void
    {
        if ($this->projectId) {
            $this->project = Project::find($this->projectId);
        }
    }

    #[On('document-uploaded')]
    public function refreshOnDocumentUpload(): void
    {
        // Refresh project to update hasSPKDocument check
        if ($this->projectId) {
            $this->project = Project::find($this->projectId);
        }
    }

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function sortData($sort): void
    {
        if ($this->sort === $sort && $this->sortDirection === 'asc') {
            $this->sortDirection = 'desc';
        } elseif ($this->sort === $sort && $this->sortDirection === 'desc') {
            $this->reset('sort', 'sortDirection');

            return;
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sort = $sort;
    }

    public function delete($token): void
    {
        try {
            $id = ProjectInvoice::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $invoice = ProjectInvoice::findOrFail($id);

            DB::transaction(function () use ($invoice) {
                $invoiceArray = $invoice->toArray();

                // Delete invoice items first
                $invoice->items()->delete();
                $invoice->invoiceDocument?->delete();
                $invoice->recetipDocument?->delete();
                $invoice->delete();

                SynAuth::logActivity(
                    Auth::id(),
                    'pm.project.'.$this->project->id.'.invoice.delete',
                    'User '.Auth::user()->name.' deleted invoice data. Data: '.json_encode($invoiceArray),
                    'project.'.$this->project->id.'.invoice',
                    'pm',
                );
            });

            $this->dispatch('synapse-confirmed');
            $this->dispatch('activity-refresh');

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::invoice.deleted_successfully'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Computed()]
    public function invoiceList()
    {
        // $query = ProjectInvoice::with(['issuer', 'creator', 'client', 'project']);
        $query = ProjectInvoice::query();

        // Filter by project if projectId is set
        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        // Apply search by invoice number
        if (! empty($this->q) && strlen($this->q) > 2) {
            $query->where('invoice_number', 'like', "%{$this->q}%");
        }

        // Apply status filter
        if ($this->filterStatus === 'paid') {
            $query->where('is_paid', true);
        } elseif ($this->filterStatus === 'unpaid') {
            $query->where(function ($q) {
                $q->where('is_paid', false)->orWhereNull('is_paid');
            });
        } elseif ($this->filterStatus === 'partial') {
            // Partial payment no longer supported - treat as unpaid
            $query->where(function ($q) {
                $q->where('is_paid', false)->orWhereNull('is_paid');
            });
        }

        // Apply year filter
        if (! empty($this->filterYear)) {
            $query->whereYear('created_at', $this->filterYear);
        }

        // Apply issuer filter
        if (! empty($this->filterIssuer)) {
            $query->where('issuer_id', $this->filterIssuer);
        }

        // Apply creator filter
        if (! empty($this->filterCreator)) {
            $query->where('created_by', $this->filterCreator);
        }

        return $query
            ->orderBy($this->sort, $this->sortDirection)
            ->paginate($this->limit)
            ->onEachSide(1);
    }

    #[Computed()]
    public function availableYears()
    {
        $driver = \DB::connection()->getDriverName();

        // SQLite uses strftime, MySQL/PostgreSQL use YEAR()
        $yearExpression = $driver === 'sqlite'
            ? "strftime('%Y', created_at)"
            : 'YEAR(created_at)';

        return ProjectInvoice::selectRaw("DISTINCT {$yearExpression} as year")
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->toArray();
    }

    #[Computed()]
    public function issuers()
    {
        return DocumentIssuer::select('id as value', 'name as label')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    #[Computed()]
    public function creators()
    {
        $userModel = config('auth.providers.users.model');

        // Get admin role level (typically level 1, dev is level 0)
        $adminRole = \VmEngine\SynAuth\Models\Role::where('slug', 'admin')->first();
        $adminLevel = $adminRole?->level ?? 1;

        return $userModel::select('users.id as value', 'users.name as label')
            ->whereIn('users.id', ProjectInvoice::select('created_by')->distinct())
            ->whereHas('roles', function ($query) use ($adminLevel) {
                $query->where('level', '<=', $adminLevel);
            })
            ->orderBy('users.name')
            ->get()
            ->toArray();
    }

    public function getPaymentStatus($invoice): array
    {
        if ($invoice->is_paid) {
            return [
                'label' => __('pm::invoice.status_paid'),
                'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                'icon' => 'fa-check-circle',
            ];
        }

        return [
            'label' => __('pm::invoice.status_unpaid'),
            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'icon' => 'fa-times-circle',
        ];
    }

    public function render()
    {
        return view('pm::livewire.project.invoice-list', [
            'project' => Project::find($this->projectId),
        ]);
    }
}
