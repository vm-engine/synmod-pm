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

### Client User Status Sync
```bash
# Sync all client user active status
php artisan mod-pm:sync-client-user-status

# With detailed output for each client
php artisan mod-pm:sync-client-user-status --show-details

# Force update all users regardless of current status
php artisan mod-pm:sync-client-user-status --force

# Both detailed output and force update
php artisan mod-pm:sync-client-user-status --force --show-details
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

### Client CRUD System

The Client CRUD implementation follows Laravel Synapse's standalone page pattern with a Form Object architecture:

**Components:**
- `ClientList` - Livewire component for client listing with advanced filtering
- `ClientForm` - Livewire component for create/edit forms
- `ClientFormObject` - Form class handling business logic and validation

**Key Features:**
1. **Dual-Model Management**: Creates/updates both Client and User models in single transaction
2. **Auto User Creation**: Automatically creates user account when creating client
3. **Role Assignment**: Assigns client role from `dbconf('pm.client_role_id')`
4. **Status Enforcement**:
   - Prospect clients → user.active = false (always)
   - Active clients with active=true → user.active = true
   - Active clients with active=false → user.active = false
5. **Email Verification**: All client users have `email_verified_at` set automatically
6. **Password Handling**: Required on create, optional on edit (blank keeps existing)

**Filtering:**
- Client categories (multi-select)
- Services (multi-select ANY match using `whereJsonContains`)
- Status (prospect/active)
- Active status (boolean)
- Full-text search across: company, address, city, director, pic

**Validation:**
- Follows synapps-auth standards for user fields
- Password: min 8 chars, mixed case, numbers, symbols
- Username: Uses `UsernameRule` validation
- Mode-based username requirement (respects `config('synapps.auth.login_credential')`)

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
├── Console/Commands/
│   ├── MigrateOldDataCommand.php          # Data migration from legacy DB
│   └── SyncClientUserStatusCommand.php    # Sync client user active status
├── Form/
│   └── ClientFormObject.php               # Client form business logic
├── Livewire/
│   ├── ClientList.php                     # Client listing component
│   ├── ClientForm.php                     # Client form component
│   └── Master/
│       ├── Division.php                   # Division master data
│       └── ClientCategory.php             # Client category master data
├── Models/
│   ├── Client.php                         # Client model
│   ├── ClientCategory.php                 # Client category model
│   ├── Division.php                       # Division model
│   ├── SubDivision.php                    # Sub division model
│   └── Project.php                        # Project model
├── Enums/
│   └── Services.php                       # Services enum
└── PmServiceProvider.php                  # Service provider

database/
├── migrations/                            # Database schema (timestamped)
├── factories/                             # Model factories for testing
└── seeders/                              # Database seeders (idempotent)

routes/
├── web.php                               # Frontend routes
└── web.backend.php                       # Backend admin routes

resources/views/livewire/
├── client-list.blade.php                 # Client list view
├── client-form.blade.php                 # Client form view
└── master/                               # Master data views

lang/en/
├── acl.php                               # ACL translations
├── labels.php                            # Field label translations
├── menu.php                              # Menu label translations
├── client.php                            # Client-specific translations
└── messages.php                          # System messages
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

### Livewire Component Registration
**CRITICAL:** All Livewire components MUST be registered in the `PmServiceProvider` class.

When creating a new Livewire component:
1. Import the component class at the top of `src/PmServiceProvider.php`
2. Register it in the `registerComponents()` method using `Livewire::component()`

Example:
```php
// In PmServiceProvider.php imports
use VmEngine\Pm\Livewire\Master\ProjectCategory;

// In registerComponents() method
Livewire::component('pm::master.project-category', ProjectCategory::class);
```

Component naming convention:
- Main components: `pm::component-name` (e.g., `pm::client-list`, `pm::project-form`)
- Master data components: `pm::master.component-name` (e.g., `pm::master.division`)

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
