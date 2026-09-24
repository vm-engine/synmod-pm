# Semantic CSS Plan — synmod-pm

Same method as `packages/synapse` (see `.claude/plans/2026-08-25-synapse-semantic-css.md`) and the auth/queue/cms/fm passes: tier 0 = existing `<x-synapse-*>` components, tier 1 = existing semantic classes, tier 2 = new generic classes in synapse's `synapse-components.css`. pm has **no** CSS file of its own — the old `pm-components.css` / `syn-pm-*` idea is superseded.

## Status

- **Phase 1: done** (2026-09-24) — plan: `.claude/plans/2026-09-24-synmod-pm-semantic-css-phase1.md`
- **Phase 2: not started** — `project/project-detail`, `project/invoice-detail`, `project/receipt-detail`, `report/invoice`, `config-page`
- **Never in scope:** `resources/views/pdf/*` (rendered by dompdf, which can't load the Vite-built CSS)

## Phase 1 summary

23 views: client-list/form, project-list/form, invoice-list/form, activity, report/project-document, the 9 `master/*` views, versioned-/multiple-document, document-manager, and the 3 `document-*` partials. The scaffold `livewire/index` page (and its `/apps/pm` route) was deleted — it dumped `config('database.connections')`.

- **Components adopted:** `<x-synapse-panel>` (list shells, document group card — now collapsible via the panel), `<x-synapse-search-box>`, `<x-synapse-per-page-selector>` (table lists), `<x-synapse-badge>`, `<x-synapse-alert>`.
- **Existing classes adopted:** `.syn-panel-body`, `.syn-modal-header/-title/-close/-body/-footer`, `.syn-empty-state*`, `.syn-per-page-selector`/`-select`, `.form-label`, `.form-help`, `.form-error-message`, `datatable-col-actions`, `.btn.sm`, `.syn-spin`.
- **New synapse classes:** `.syn-row-muted`, `.syn-field-caption`, `.syn-media-card` (+ `-img`, `-placeholder`, `-body`, `-title`, `-actions`), `.syn-icon-dropdown` / `.syn-icon-dropdown-item`.
- **Dead classes fixed:** `btn-sm` → `sm`, `btn-outline` → `secondary`, `icon-dropdown(-item)` (never ported from member4vm's `lta.css` — the invoice row menu was unpositioned) → `syn-icon-dropdown(-item)`.
- **Per-page split:** client-list / invoice-list / activity use `<x-synapse-per-page-selector>` (`$limit` → `$perPage`; options now 10/15/25/50). Card-grid lists (project, issuer, signature, bank-account) keep their own multiple-of-6 `<select>` with the tier-1 classes and `$limit`.
- **Icons:** literal FontAwesome in these views → Phosphor. Deferred to Phase 2 (shared with its pages): `Documents::icon()`, invoice status icons (`invoice-list.php`), `confirm-dialog` `icon:` strings, drawer `$advanceTitle` HTML, and `wire:loading.class` glyph toggles.
- **Kept on purpose:** floating `card fixed bottom-0 right-0 m-5` action card (ExampleForm reference pattern), project-list gradient status/priority pills + doc chips, report filter `card`, activity modal label/value pairs (detail-list primitive belongs to Phase 2).
- **Also removed:** project-form's leftover "Debug info (remove after testing)" block that printed division id / sub-division count to users.

### Counts (Phase 1 files, before → after)

| Metric | Before | After |
| --- | --- | --- |
| `class=` attributes | 1156 | 1068 |
| Inline utility tokens | 2627 | 1351 (−49%) |

## Original inventory (history)

Top 8 files by `class=` density when this doc was first written:

| File | `class=` occurrences |
| --- | --- |
| `resources/views/components/project/project-detail/project-detail.blade.php` | 168 |
| `resources/views/components/project/invoice-detail/invoice-detail.blade.php` | 162 |
| `resources/views/components/report/invoice/invoice.blade.php` | 148 |
| `resources/views/components/project/project-form/project-form.blade.php` | 148 |
| `resources/views/components/config-page/config-page.blade.php` | 101 |
| `resources/views/components/project/receipt-detail/receipt-detail.blade.php` | 88 |
| `resources/views/components/project/invoice-list/invoice-list.blade.php` | 92 |
| `resources/views/components/project/project-list/project-list.blade.php` | 77 |

The "491 failing tests" debt noted here originally was an install problem (pm wasn't required by the root app); once installed, the suite was green bar one stale test.
