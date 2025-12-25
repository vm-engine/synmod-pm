# Pm

Pm module for Laravel Synapse

## Installation

For package modules:

```bash
composer require vm-engine/synmod-pm
php artisan synapps:discover
php artisan config:cache
php artisan migrate
```

For internal modules, the module is auto-discovered.

## Commands

### Sync Client User Status
Synchronize all client user active status based on client status and active flags:

```bash
# Basic sync
php artisan mod-pm:sync-client-user-status

# With detailed output
php artisan mod-pm:sync-client-user-status --show-details

# Force update all users
php artisan mod-pm:sync-client-user-status --force

# Both detailed and force
php artisan mod-pm:sync-client-user-status --force --show-details
```

### Migrate Old Data
Migrate data from legacy database to current schema:

```bash
# Basic migration
php artisan mod-pm:migrate-old {source_connection}

# Skip missing foreign key references
php artisan mod-pm:migrate-old {source_connection} --skip-missing

# Example
php artisan mod-pm:migrate-old mysql_old --skip-missing
```

## Features

### Master Data Management
- **Divisions**: Organizational divisions management
- **Sub Divisions**: Subdivisions within divisions
- **Client Categories**: Client categorization system

### Client Management
- **Client CRUD**: Complete client management with user account creation
- **Client Categories**: Organize clients by categories
- **Services Tracking**: Multi-select service assignments (Design, Development, SEO, etc.)
- **Status Management**: Prospect vs Active client status
- **Advanced Filtering**: Filter by category, services, status, and active state
- **User Integration**: Auto-create user accounts with role assignment
- **Active Status Sync**: Client active status syncs with user account status

### Business Logic
- Prospect clients automatically have inactive user accounts
- Active clients with active flag enabled have active user accounts
- Email verification is automatic for all client users
- Password requirements follow synapps-auth standards

## Requirements

- Laravel 11+
- Livewire v3
- Laravel Synapse

## License

MIT

## Author

**Achmad Ardiansyah**
Email: theadods@gmail.com

## Version

0.2.0
