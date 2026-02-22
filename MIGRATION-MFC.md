# Migration Plan: synmod-pm to Synapse v2.0 / Livewire v4 MFC Format

## Context

The PM module currently uses 24 class-based Livewire components in `src/Livewire/` with views in `resources/views/livewire/`. Synapse v2.0 introduced MFC (Multi-File Component) format as the standard. This migration converts all components to MFC format using Livewire v4's native namespace system (`Livewire::addNamespace()`), adds session-based parameter preserving via `RemembersQueryParams` for list pages, and preserves the existing subfolder grouping.

## Key Decisions

### Component Registration: Livewire Namespace
Instead of the current `AutoRegistersComponents` custom scanning, register the module using Livewire v4's native namespace:

```php
// In PmServiceProvider
Livewire::addNamespace('pm', viewPath: __DIR__.'/../resources/views/components');
```

This enables:
- Native recursive MFC discovery by Livewire
- Nested subdirectories map to dot notation: `master/division/` → `pm::master.division`
- Components referenced with `::` syntax: `<livewire:pm::client-list />`, `<livewire:pm::master.division />`
- Routes use: `Route::livewire('/path', 'pm::component-name')`

### MFC Directory Structure (preserving subfolder grouping)

```
resources/views/components/
├── client-list/client-list.php + .blade.php         → pm::client-list
├── client-form/client-form.php + .blade.php         → pm::client-form
├── config/config.php + .blade.php                   → pm::config
├── master/
│   ├── division/division.php + .blade.php           → pm::master.division
│   ├── client-category/client-category.php + .blade.php → pm::master.client-category
│   ├── project-category/project-category.php + .blade.php → pm::master.project-category
│   ├── issuer-list/issuer-list.php + .blade.php     → pm::master.issuer-list
│   ├── issuer-form/issuer-form.php + .blade.php     → pm::master.issuer-form
│   ├── bank-account-list/bank-account-list.php + .blade.php → pm::master.bank-account-list
│   ├── bank-account-form/bank-account-form.php + .blade.php → pm::master.bank-account-form
│   ├── signature-list/signature-list.php + .blade.php → pm::master.signature-list
│   └── signature-form/signature-form.php + .blade.php → pm::master.signature-form
├── project/
│   ├── project-list/project-list.php + .blade.php   → pm::project.project-list
│   ├── project-form/project-form.php + .blade.php   → pm::project.project-form
│   ├── project-detail/project-detail.php + .blade.php → pm::project.project-detail
│   ├── invoice-list/invoice-list.php + .blade.php   → pm::project.invoice-list
│   ├── invoice-form/invoice-form.php + .blade.php   → pm::project.invoice-form
│   ├── invoice-detail/invoice-detail.php + .blade.php → pm::project.invoice-detail
│   ├── receipt-detail/receipt-detail.php + .blade.php → pm::project.receipt-detail
│   ├── document-manager/document-manager.php + .blade.php → pm::project.document-manager
│   ├── multiple-document/multiple-document.php + .blade.php → pm::project.multiple-document
│   ├── versioned-document/versioned-document.php + .blade.php → pm::project.versioned-document
│   └── activity/activity.php + .blade.php           → pm::project.activity
├── report/
│   ├── invoice/invoice.php + .blade.php             → pm::report.invoice
│   └── project-document/project-document.php + .blade.php → pm::report.project-document
```

### Name Mapping (Old → New)

| Old Name | New Name |
|---|---|
| `pm.client-list` | `pm::client-list` |
| `pm.client-form` | `pm::client-form` |
| `pm.config` | `pm::config` |
| `pm.master.division` | `pm::master.division` |
| `pm.master.client-category` | `pm::master.client-category` |
| `pm.master.project-category` | `pm::master.project-category` |
| `pm.master.issuer` | `pm::master.issuer-list` |
| `pm.master.issuer-form` | `pm::master.issuer-form` |
| `pm.master.bank-account` | `pm::master.bank-account-list` |
| `pm.master.bank-account-form` | `pm::master.bank-account-form` |
| `pm.master.signature` | `pm::master.signature-list` |
| `pm.master.signature-form` | `pm::master.signature-form` |
| `pm.project.project-list` | `pm::project.project-list` |
| `pm.project.project-form` | `pm::project.project-form` |
| `pm.project.project-detail` | `pm::project.project-detail` |
| `pm.project.invoice-list` | `pm::project.invoice-list` |
| `pm.project.invoice-form` | `pm::project.invoice-form` |
| `pm.project.invoice-detail` | `pm::project.invoice-detail` |
| `pm.project.receipt-detail` | `pm::project.receipt-detail` |
| `pm.project.document-manager` | `pm::project.document-manager` |
| `pm.project.multiple-document` | `pm::project.multiple-document` |
| `pm.project.versioned-document` | `pm::project.versioned-document` |
| `pm.project.activity` | `pm::project.activity` |
| `pm.report.invoice` | `pm::report.invoice` |
| `pm.report.project-document` | `pm::report.project-document` |

### What Stays Unchanged
- **Form objects** (`src/Form/*FormObject.php`) - referenced by MFC anonymous classes via `use`
- **Models, Services, Controllers, Console Commands, Middleware** - no changes
- **Existing standalone Blade components** (`resources/views/components/*.blade.php`) - these are anonymous Blade components, not MFC dirs, no conflict

## Transformation Patterns

### Pattern A: Class → MFC anonymous class, render() → mount() + #[Computed]
```php
// OLD: src/Livewire/ClientList.php
namespace VmEngine\Pm\Livewire;
class ClientList extends Component {
    public function render() {
        $categories = ClientCategory::select(...)->get()->toArray();
        synav()->setActiveMenu('pm.client.index');
        return view('pm::livewire.client-list', [
            'categories' => $categories,
        ])->title(page_title(__('pm::client.list_title')));
    }
}

// NEW: resources/views/components/client-list/client-list.php
<?php
use Livewire\Component;
new class extends Component {
    public function mount(): void {
        synav()->setActiveMenu('pm.client.index');
    }
    public function title(): string {
        return page_title(__('pm::client.list_title'));
    }
    #[Computed()]
    public function categories(): array {
        return ClientCategory::select(...)->get()->toArray();
    }
};
```

### Pattern B: dispatch() normalization
```php
// OLD (array style)
$this->dispatch('notify', ['variant' => 'success', 'title' => 'Success', 'message' => 'Done']);
// NEW (named params)
$this->dispatch('notify', variant: 'success', title: 'Success', message: 'Done');
```

### Pattern C: Routes
```php
// OLD
use VmEngine\Pm\Livewire\ClientList;
Route::get('/client', ClientList::class)->name('client.index');
// NEW
Route::livewire('/client', 'pm::client-list')->name('client.index');
```

### Pattern D: RemembersQueryParams for lists
```php
use VmEngine\Synapse\Traits\RemembersQueryParams;
new class extends Component {
    use RemembersQueryParams, WithPagination;
    #[Url()] public $q;
    #[Url()] public int $limit = 10;
    #[Url()] public string $sort = 'id';
    #[Url()] public string $sortDirection = 'desc';
    protected function rememberedParams(): array {
        return ['q', 'filterCategories', 'limit', 'sort', 'sortDirection'];
    }
    protected function rememberKey(): string {
        return 'synapse:query_params:pm.client-list';
    }
};
```

### Pattern E: Form redirect (no WithReturnUrl needed)
Since list pages use `RemembersQueryParams`, forms just redirect to the list route. The list restores filters from session automatically.
```php
public function save(): void {
    // ... save ...
    $this->redirectRoute('backend.pm.client.index', navigate: true);
}
```

### Pattern F: Blade component references
```blade
{{-- OLD --}}
<livewire:pm.project.activity :projectId="$id" />
@livewire('pm.master.issuer-form')

{{-- NEW --}}
<livewire:pm::project.activity :projectId="$id" />
<livewire:pm::master.issuer-form />
```

## Implementation Phases

### Phase 1: Service Provider + Routes
**Files:**
- `src/PmServiceProvider.php` - Replace `AutoRegistersComponents` trait usage with `Livewire::addNamespace('pm', viewPath: ...)`
- `routes/web.backend.php` - Convert `Route::get('/path', Class::class)` → `Route::livewire('/path', 'pm::component-name')`, remove class imports

### Phase 2: Simple Master Pages (3 components)
Self-contained pages with inline editing:
1. **Division** → `resources/views/components/master/division/`
2. **ClientCategory** → `resources/views/components/master/client-category/`
3. **ProjectCategory** → `resources/views/components/master/project-category/`

Each: create MFC dir, write anonymous class `.php`, move `.blade.php`, convert render→computed, add mount/title, normalize dispatch calls.

### Phase 3: Master List + Modal Form Pairs (6 components)
4. **IssuerForm** → `resources/views/components/master/issuer-form/`
5. **Issuer** → `resources/views/components/master/issuer-list/` (+ RemembersQueryParams)
6. **BankAccountForm** → `resources/views/components/master/bank-account-form/`
7. **BankAccount** → `resources/views/components/master/bank-account-list/` (+ RemembersQueryParams)
8. **SignatureForm** → `resources/views/components/master/signature-form/`
9. **Signature** → `resources/views/components/master/signature-list/` (+ RemembersQueryParams)

Update blade `@livewire` refs to use `pm::master.*` syntax.

### Phase 4: Client Components (2 components)
10. **ClientList** → `resources/views/components/client-list/` (+ RemembersQueryParams)
11. **ClientForm** → `resources/views/components/client-form/` (simple redirectRoute)

### Phase 5: Project Components (4 components)
12. **ProjectList** → `resources/views/components/project/project-list/` (+ RemembersQueryParams)
13. **ProjectForm** → `resources/views/components/project/project-form/` (simple redirectRoute)
14. **ProjectDetail** → `resources/views/components/project/project-detail/` (update child refs)
15. **DocumentManager** → `resources/views/components/project/document-manager/` (update child refs)

### Phase 6: Invoice/Receipt Components (4 components)
16. **InvoiceList** → `resources/views/components/project/invoice-list/` (child component)
17. **InvoiceForm** → `resources/views/components/project/invoice-form/`
18. **InvoiceDetail** → `resources/views/components/project/invoice-detail/` (most complex)
19. **ReceiptDetail** → `resources/views/components/project/receipt-detail/`

### Phase 7: Document + Activity + Reports + Config (5 components)
20. **MultipleDocument** → `resources/views/components/project/multiple-document/`
21. **VersionedDocument** → `resources/views/components/project/versioned-document/`
22. **Activity** → `resources/views/components/project/activity/`
23. **Report/Invoice** → `resources/views/components/report/invoice/`
24. **Report/ProjectDocument** → `resources/views/components/report/project-document/`
25. **Config** → `resources/views/components/config/`

### Phase 8: Cleanup
- Delete `src/Livewire/` directory entirely
- Delete old blade views from `resources/views/livewire/` (keep `index.blade.php` dashboard)
- Run PHPStan analyse (`vendor/bin/phpstan analyse --level=5`)
- Run Pint formatting (`vendor/bin/pint`)
- Run Pest tests (`pest`)

## Components Getting RemembersQueryParams

| Component | Remembered Params |
|---|---|
| `pm::client-list` | `q`, `filterCategories`, `filterServices`, `filterStatus`, `filterActive`, `limit`, `sort`, `sortDirection` |
| `pm::project.project-list` | `q`, `filterDivisions`, `filterClients`, `filterServices`, `filterStatuses`, `filterPriorities`, `filterYear`, `filterProgress`, `limit`, `sort`, `sortDirection` |
| `pm::master.issuer-list` | `q`, `limit` |
| `pm::master.bank-account-list` | `q`, `limit` |
| `pm::master.signature-list` | `q`, `limit` |

## Blade Cross-References to Update

- **project-detail.blade.php**: `pm.project.activity` → `pm::project.activity`, `pm.project.document-manager` → `pm::project.document-manager`, `pm.project.invoice-list` → `pm::project.invoice-list`
- **document-manager.blade.php**: old refs → `pm::project.versioned-document`, `pm::project.multiple-document`
- **issuer-list.blade.php**: `pm.master.issuer-form` → `pm::master.issuer-form`
- **bank-account-list.blade.php**: `pm.master.bank-account-form` → `pm::master.bank-account-form`
- **signature-list.blade.php**: `pm.master.signature-form` → `pm::master.signature-form`
- **All `->to()` dispatch targets** must use new `pm::*` names

## Verification

1. Run `pest` from package root - verify DiscoveryTest passes
2. Run `vendor/bin/phpstan analyse --level=5` from package root
3. Run `vendor/bin/pint` from package root
4. Manual testing: navigate all pages (client list, project list, master pages, reports, config)
5. Verify RemembersQueryParams: apply filters on list → navigate to detail → press back → filters restored
6. Verify form→list flow: apply filters on list → click edit → save → redirects to list → filters restored from session
