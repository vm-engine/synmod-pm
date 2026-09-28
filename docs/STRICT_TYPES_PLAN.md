# Strict Types Plan — synmod-pm

> **Status (2026-09-27): done.** Every tracked PHP file declares `strict_types`, and the package's `phpstan.neon` passes level 7 with no ignore list. Strict mode surfaced one real bug: `Documents\Base::generateDocumentNumber()` passed `dbconf()` strings into typed parameters; they are now cast. Kept for history.

Same pattern as `packages/synapse` (see that package's `phpstan.neon`, now level 7).

## Scope

- **Largest module by far**: 217 in-repo PHP files, 58 missing `declare(strict_types=1)`.
- Concentrated in `src/Services` (18 files), `database/migrations` (14), `src/Enums` (3) — `src/Services` is the risk zone: that's where synapse's real strict-types breakage (`is_decimal()`/`floor()` coercion) lived, so expect similar coercion bugs here, not just missing-annotation noise.
- Has its own `phpstan.neon` at **level 5**. Per `CLAUDE.md`, run Pint/PHPStan/Pest from the **main Laravel project root**; bump this package's own `phpstan.neon` `level: 5` → `level: 6` to match synapse's depth.
- This module was just synced to the `v3-development` branch (Aug 25) to align with other packages — do this work as a separate follow-up commit, not bundled into that sync.

## Steps (mirror the synapse execution)

1. Bump `packages/synmod-pm/phpstan.neon` `level: 5` → `level: 6`.
2. Add `declare(strict_types=1);` to the 58 files above — do `src/Services` first and in isolation (smaller diff to review) given the coercion risk, then the rest.
3. After each batch: `php -l` sweep + run this module's Pest suite from main root before moving to the next batch — don't add strict_types to all 217 files and find out what broke at the end.
4. `vendor/bin/phpstan analyse packages/synmod-pm --error-format=raw --no-progress` from main root; fix findings. Given 217 files, expect the error count to be proportionally larger than synapse's 201 — budget this as its own multi-session task, not a quick pass.
5. `vendor/bin/pint packages/synmod-pm --format agent` from main root.
6. Re-run Pest to confirm green.

## Out of scope for this doc

Actually applying the fixes — inventory only. Given the size, consider splitting the PHPStan-fix step itself into a `/loop` or a separate execution session rather than one sitting.
