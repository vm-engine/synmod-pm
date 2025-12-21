# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Laravel Synapse Package Module** for Project Management (PM). It's designed to work within the Laravel Synapse ecosystem and follows the package module architecture pattern (`synmod-*` prefix).

**Key Identity:**
- Package name: `vm-engine/synmod-pm`
- Module ID: `pm`
- Namespace: `VmEngine\Pm`
- Type: `synapse-module` (Composer package type)

## Development Commands

### Code Formatting
```bash
vendor/bin/pint                    # Format all PHP files
vendor/bin/pint src/Models/        # Format specific directory
vendor/bin/pint --dirty            # Format only changed files
```

### Testing
```bash
pest                               # Run all tests
pest --filter=DiscoveryTest        # Run specific test
pest tests/Feature/                # Run specific directory
```

### Data Migration
```bash
# Migrate data from old database to new schema
php artisan mod-pm:migrate-old {source_connection} [--skip-missing]

# Example:
php artisan mod-pm:migrate-old mysql_old
php artisan mod-pm:migrate-old mysql_old --skip-missing
```

### Module Discovery (in parent Laravel app)
```bash
php artisan synapps:discover       # Discover and register Synapse modules
php artisan config:cache           # Cache configuration
php artisan migrate                # Run migrations
```

## Architecture

### Laravel Synapse Module System

This module uses Laravel Synapse's auto-discovery system. The module is defined in `module.json` which contains:
- Module metadata (id, name, version)
- Navigation structure (backend menus)
- ACL (Access Control List) definitions
- URL prefix configuration

The module is auto-discovered through the service provider (`PmServiceProvider`) registered in `composer.json`.

### Database Schema Design

**Entity Relationships:**
```
divisions (1) ──→ (n) sub_divisions
divisions (1) ──→ (n) projects
client_categories (1) ──→ (n) clients
users (1) ──→ (n) clients
users (1) ──→ (n) projects
```

**Key Tables:**
- `divisions` - Main organizational divisions
- `sub_divisions` - Subdivisions within divisions
- `client_categories` - Categories for organizing clients
- `clients` - Client records with JSON fields (services, social_media)
- `projects` - Projects with JSON sub_division array

**Important Model Features:**

1. **SynAuth Compatibility**: All models use `config('auth.providers.users.model')` instead of hardcoded User class for relationship definitions.

2. **Client Model Status System**:
   - Constants: `STATUS_PROSPECT = 0`, `STATUS_ACTIVE = 1`
   - Helper methods: `isProspect()`, `isActive()`
   - Query scopes: `prospect()`, `activeStatus()`, `active()`, `inactive()`
   - Factory states for all status combinations

3. **Audit Fields**: All models except User have `created_by` and `updated_by` foreign keys.

4. **JSON Casting**:
   - `clients.services` - Array
   - `clients.social_media` - Array
   - `projects.sub_division` - Array of integers

### Data Migration System

The `MigrateOldDataCommand` performs idempotent migration from legacy database:

**Features:**
- Tests database connection before proceeding
- Migrates in dependency order: users → divisions → sub_divisions → clients → projects
- Automatically assigns 'client' role to users with client records
- Checks if client role exists; runs `ClientRoleSeeder` if missing
- Uses `insertOrIgnore` for idempotent role assignment
- Handles missing foreign key references with `--skip-missing` option
- Maps old schema to new schema (e.g., `photo` → `avatar`, `active` → `status`)
- Shows progress bars and detailed statistics

**Data Transformations:**
- `services`: JSON string → array
- `social_media`: JSON string → array
- `sub_division`: Comma-separated string → JSON array of integers
- `active` boolean → `status` integer
- `photo` → `avatar`

### Seeders and Factories

**Seeder Idempotency:**
All seeders use `firstOrCreate()` to prevent duplicates when run multiple times. This preserves original records and prevents data loss.

**Factory States:**
ClientFactory includes states for different scenarios:
- `prospect()` - Sets status to prospect
- `active()` - Sets status to active
- `inactive()` - Sets active to false
- `withProposal()` - Sets is_proposal to true

### Module File Structure

```
src/
├── Console/Commands/     # Artisan commands
├── Models/              # Eloquent models with relationships
└── PmServiceProvider.php # Service provider (registers commands)

database/
├── migrations/          # Database schema (timestamped)
├── factories/          # Model factories for testing
└── seeders/           # Database seeders (idempotent)

routes/
├── web.php            # Frontend routes
└── web.backend.php    # Backend admin routes

resources/views/livewire/  # Livewire component views

lang/
├── en/acl.php         # ACL translations
├── en/labels.php      # Field label translations
└── en/menu.php        # Menu label translations
```

## Important Conventions

### User Model References
Always use `config('auth.providers.users.model')` for User relationships to maintain SynAuth compatibility:

```php
public function user(): BelongsTo
{
    return $this->belongsTo(config('auth.providers.users.model'));
}
```

### Module Configuration
The `module.json` file defines:
- Navigation menus with translation keys (prefix: `__pm::`)
- ACL permissions linked to navigation
- URL prefix for routes

### Naming Conventions
- Models: Singular (Client, Division, Project)
- Tables: Plural (clients, divisions, projects)
- Factories: ModelNameFactory
- Seeders: ModelNameSeeder
- Migrations: Follow Laravel timestamp convention

## Testing

Tests use Pest framework. The module includes:
- `DiscoveryTest.php` - Verifies module auto-discovery works correctly

When adding tests:
- Feature tests go in `tests/Feature/`
- Use factories for model creation
- Ensure tests are idempotent

## Documentation

- `README.md` - Installation and basic usage
- `MIGRATION.md` - Detailed data migration guide with troubleshooting
- `CHANGELOG.md` - Version history

## Dependencies

**Required:**
- PHP ^8.2
- Laravel ^11.0|^12.0
- Livewire ^3.0
- Laravel Synapse (`vm-engine/synapse`)

**Development:**
- Pest ^3.0|^4.0
- Laravel Pint ^1.0
