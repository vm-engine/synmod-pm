# Semantic CSS Plan — synmod-pm

Same pattern as `packages/synapse` (see `.claude/plans/2026-08-25-synapse-semantic-css.md`). Not implemented yet — inventory only. This is the largest and most Tailwind-heavy module in the project.

## Candidate files (by inline utility-class density, top 8)

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

Note: `synmod-pm` currently has known PHPStan/test-suite debt (see recent session notes: 491 failing tests, PHPStan level 5 non-compliance) — do NOT start this CSS work until that debt is resolved, so CSS changes aren't reviewed against a red test suite.

## Proposed convention
- Prefix: `syn-pm-*`.
- The `*-detail`/`*-form`/`*-list` trio repeats across `project`, `invoice`, `receipt` — strongly suggests 3 shared component patterns (detail page, form page, list page) rather than 9 independent extractions. Scope the follow-up plan around those 3 shared patterns first.
- New file: `packages/synmod-pm/resources/css/pm-components.css`.

## Out of scope for this doc
Full class-by-class extraction — follow-up plan once confirmed, and once the module's test suite is green.
