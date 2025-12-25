# Changelog

All notable changes to this project will be documented in this file.

## [0.2.0] - 2025-12-25

### Added
- **Client CRUD System**: Complete client management with user account integration
  - Two-column form layout (client data + user data)
  - Auto-create user accounts when creating clients
  - Role assignment from `dbconf('pm.client_role_id')`
  - Advanced filtering: categories, services (multi-select ANY), status, active
  - Full-text search across company, address, city, director, PIC
  - Inline active toggler syncing client and user status
  - Social media as array (one URL per line)
  - Services multiselect using JSON array with `whereJsonContains`
- **Status Enforcement Logic**:
  - Prospect clients → user.status = false (always inactive)
  - Active clients with active=true → user.status = true
  - Active clients with active=false → user.status = false
- **Email Verification**: All client users have `email_verified_at` set automatically
- **Password Validation**: Follows synapps-auth standards (min 8, mixed case, numbers, symbols)
- **SyncClientUserStatus Command**: Artisan command to sync all client user statuses
  - `--show-details` flag for detailed output
  - `--force` flag to force update all users
- **Translation System**: Comprehensive translations in `lang/en/client.php` and `lang/en/messages.php`
- **Documentation**: Updated README.md and CLAUDE.md with complete feature documentation

### Fixed
- User status field: Changed from `active` to `status` field for user table
- Form validation: Password optional on edit mode
- Array format: Client categories use key=>value format for synapse-select
- Code formatting: All files formatted with Laravel Pint

## [0.1.0] - 2025-12-20

### Added
- Initial release
- Module structure setup
