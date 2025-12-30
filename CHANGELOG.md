# Changelog

All notable changes to this project will be documented in this file.

## [0.3.1] - 2025-12-30

### Added
- **Data Migration Command**: `mod-pm:migrate-old` for migrating legacy database
  - Migrate users, divisions, sub-divisions, clients from old schema
  - Convert `proposal` table to projects with PROPOSAL documents
  - Migrate SPH documents with version tracking
  - Migrate SPK documents with version tracking and deal status
  - Auto-update project status from PROSPECT to PLANNING when SPK has `is_deal = 1`
  - Auto-activate clients when SPK has `is_deal = 1`
  - File migration from `public/document/` to `storage/app/private/projects/{id}/documents/{type}/`
  - Idempotent operations using `updateOrCreate()` for safe re-runs
  - Progress tracking with detailed statistics
  - `--skip-missing` option to skip records with missing references

### Fixed
- **Document Revision List**: Fixed version history display showing all versions instead of only version 1
  - Loop through all documents using `$documents->skip(1)`
  - Properly display version numbers for each historical revision
- **Project List UI**: Enhanced card-based layout with document badges
  - Show colorful gradient badges for document types
  - Display file counter badges for multiple files
  - Hide document section when no documents exist
  - Fixed tooltips appearing on individual buttons instead of entire card
- **Migration Text Cleaning**: Improved name cleaning for proposals
  - Remove "PROPOSAL", "PENAWARAN", "SURAT", "SPH", "HARGA" words anywhere in text
  - Word boundary matching to avoid partial word replacements
  - Multiple space cleanup after word removal

### Changed
- **Storage Structure**: Files now stored in project-specific folders
  - Path: `storage/app/private/projects/{project_id}/documents/{doc_type_code}/`
  - Random hash filenames for security
  - Proper document type code organization (0, SPH, SPK)

## [0.3.0] - 2025-12-29

### Added
- **Document Management System**: Complete document upload/download/management functionality
  - ProjectDocument model with delete token trait and enum casting
  - Document handlers as stateless singletons with auto-numbering
  - Versioned documents (SPK, SOW, TOR, etc.) with revision history
  - Multiple documents (Invoice, Receipt, Backup, etc.) with grid layout
  - Upload modals with wire:loading states for better UX
  - File type-specific icons (PDF, Word, Excel, PowerPoint, Archive, Image)
- **Document Operations**:
  - Upload with auto-generated document codes
  - Download with authorization checks
  - View documents via temporary URLs (valid 5 minutes)
  - Delete with confirm dialog using delete tokens
  - Upload new versions for versioned documents
  - Restore old versions as new versions
  - Version history display with collapsible sections
- **Authorization & Security**:
  - Role-based access control (dev, admin level <10, matching client)
  - Delete token validation pattern following ClientList
  - Authorization checks for download and view operations
  - Project ownership validation on all operations
- **Event-Driven Architecture**:
  - Parent component refresh on document changes
  - Event listeners on ProjectDetail and DocumentManager
  - Real-time UI updates after upload/delete/restore
- **UI Components**:
  - Document group cards with expand/collapse
  - Document revision list for versioned documents
  - Document multiple files grid for non-versioned documents
  - Confirm dialog integration for safe deletions
  - Alpine.js integration for URL opening in new tabs

### Changed
- **Livewire V3 Compatibility**: Fixed enum property hydration
  - Store enum value as public scalar `documentTypeValue`
  - Reconstruct enum via getter method when needed
  - Removed stateful properties from components
- **Service Architecture**: Refactored all document handlers to stateless pattern
  - Base class accepts Project and Documents as method parameters
  - All 15 document implementations updated
  - Singleton pattern with dependency injection
- **Database Schema**: Migration updated from MySQL ENUM to TINYINT UNSIGNED
  - Proper integer-backed enum storage
  - Cast to Documents enum in model

### Fixed
- Modal naming conflicts between versioned and multiple document components
- Document manager refresh with static wire:key
- Confirm dialog appearing multiple times (moved to parent)
- File upload button not opening modal (code() method consistency)
- Variable naming inconsistencies in multiple-files-list template
- Code formatting with Laravel Pint (90 files, 1 style issue)

## [0.2.1] - 2025-12-25

### Added
- **Comprehensive Test Suite**: 117 tests covering all features
  - ClientList component tests (23 tests) - filtering, search, sorting, actions
  - ClientForm component tests (25 tests) - CRUD, validation, status logic
  - Client model tests (30 tests) - scopes, relationships, helper methods
  - Division CRUD tests (18 tests) - divisions & subdivisions
  - ClientCategory CRUD tests (17 tests) - category management
  - Module discovery test (1 test)
  - Delete token validation tests (3 tests)

### Fixed
- **Factory Foreign Keys**: Updated all factories to properly create users instead of hardcoding `created_by = 1`
  - ClientFactory, ClientCategoryFactory, DivisionFactory, SubDivisionFactory, ProjectFactory
- **Test Infrastructure**: Removed package-specific test configuration (TestCase.php, Pest.php, phpunit.xml)
  - Tests now properly integrate with main Laravel project
- **User Creation**: Tests use `config('auth.providers.users.model')::factory()->create()` for SynAuth compatibility
- **Testing Environment**: Added APP_KEY to `.env.testing` for encryption support

### Changed
- **Code Quality**: Fixed code formatting issues with Laravel Pint

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
