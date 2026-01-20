# Changelog

All notable changes to this project will be documented in this file.

## [0.5.1] - 2026-01-20

### Fixed
- **Project Input**: Fixed 500 Error on Create new Project

## [0.5.0] - 2026-01-20

### Added
- **Receipt Generation System**: Complete receipt management with PDF generation
  - ReceiptDetail Livewire component with payment status management
  - Receipt PDF template with centered signature section matching reference design
  - Payment tracking with datetime picker and proof upload functionality
  - Terbilang helper for Indonesian number-to-text conversion
  - Receipt routes for download, print, and payment proof viewing
  - Integration with invoice list through receipt modal
- **Invoice Management**: Full invoice system with PDF generation
  - InvoiceDetail Livewire component for managing invoice items and totals
  - InvoiceController with download, print, and regenerate endpoints
  - Invoice PDF template matching company branding style
  - Auto-fill from project/client data with document number generation
  - Invoice list embedded in project detail with create/edit modal
- **Signature Master Data**: New master data module for managing signatures
  - Signature CRUD page with form modal
  - ACL permissions for signature management
  - Integration with invoice and receipt documents
- **Document Storage Migration**: Enhanced security for project documents
  - Migrated document storage from public to local disk
  - Created secure DocumentController with authorization checks
  - MigrateProjectUploadsCommand for migrating existing files
  - Updated VersionedDocument and MultipleDocument components for local storage
- **Activity Detail Modal**: Enhanced activity logging display
  - Modal showing complete activity information
  - Display user, activity type, feature, description, IP, user agent, and timestamp
  - Added missing translation keys for activity fields
- **Comprehensive Test Coverage**: Significantly expanded test suite with 375 tests total
  - New test coverage for console commands (MigrateProjectUploadsCommand)
  - New test coverage for HTTP controllers (DocumentController, ReceiptController)
  - New test coverage for Master data Livewire components (BankAccount, ProjectCategory, Signature)
  - New test coverage for Project Livewire components (Activity, InvoiceList, ReceiptDetail)
  - New unit test for Terbilang helper function
- **PDF Generation**: Added barryvdh/laravel-dompdf package for PDF generation

### Fixed
- **Client Form Tests**: Fixed multiple critical test failures
  - Username validation now properly allows empty values on update
  - Email verification timestamp (`email_verified_at`) now correctly set on user creation
  - User status synchronization when active flag changes
  - Added missing authentication context to multiple tests
- **Client List Tests**: Fixed inactive filter test that was showing false positives
- **Client Factory**: Added `configure()` hook to automatically sync user status with client status after creation
- **Client Form Object**:
  - Fixed `email_verified_at` field handling (not in User model's fillable array)
  - Fixed user status casting to ensure proper integer values (0 or 1)
  - Improved username validation logic differentiating between create and update operations
- **Code Quality**: Applied Laravel Pint formatting fixes across test files

### Changed
- **Invoice List**: Updated payment status logic to prioritize `is_paid` field from receipts
- **Document Components**: Added readonly mode support for VersionedDocument and MultipleDocument
- **Invoice PDF Storage**: Invoices now stored on local disk instead of public disk for security
- **Test Data Quality**: Improved test data naming to avoid conflicts with UI labels
- **Factory States**: Enhanced `active()` state to explicitly set both `status` and `active` flags
- **Test Reliability**: All 375 tests now passing with 863 assertions
- **Translation Updates**: Added receipt, activity detail, and synauth activity translations

### Removed
- **ProjectReceipt Model**: Removed and consolidated functionality into invoice system
- **Issuer-specific Tables**: Removed issuer-specific bank account and signature tables in favor of standalone master data tables

## [0.4.10] - 2026-01-13

### Changed
- **UI Improvements**: Updated Tailwind CSS classes from deprecated `flex-shrink-0` to modern `shrink-0` shorthand
  - Updated across document-revision-list, client-form, bank-account-form, issuer-form, document-manager, and project-detail views
- **Code Refactoring**: Improved ProjectDocument Livewire component code readability
  - Refactored document filtering logic to use closure for better maintainability

### Fixed
- **ACL Permissions**: Fixed division master data ACL permission names
  - Changed from `pm.master.division.edit` to correct `pm.master.division.update`
- **Report Enhancements**: Enhanced project document report display
  - Added document creator name with user icon in document list
  - Improved status badges styling with color coding (success for deal, default for pending)
  - Better visual styling for uncategorized projects with gray italic text

## [0.4.9] - 2026-01-13

### Added
- **Project Document Report**: New report page for viewing project documents with advanced filtering
  - Filter by year, document type, and project category
  - Display document details with client, project, category, and status information
  - Show deal status based on SPK document presence
  - Comprehensive internationalization with language translations
- **Report Menu & Navigation**: Added report section to backend navigation with ACL permissions
- **Report Route**: New route with proper ACL middleware protection

### Changed
- **Project Model Enhancement**: Added name attribute accessor/mutator for automatic capitalization
- **Client List UX**: Added category filter preload for better user experience
- **Code Quality**: Applied Pint code formatting across multiple files

### Fixed
- Removed unused imports and standardized spacing

## [0.4.8] - 2026-01-11

## Changed
- Add missing SynAuth and Auth facade imports

## [0.4.7] - 2026-01-11

## Changed
- Updated composer.json to use proper version number instead of @dev

## [0.4.6] - 2026-01-09

### Added
- Added logging to all user activity

### Changed
- Remove readonly attribute from Document Number form

## [0.4.5] - 2026-01-09

### Fixed
- Division component now properly clears code field after creation and update
- ClientCategory component converts empty description to null
- Removed services field from ClientFormObject (temporarily disabled feature)
- Updated tests to match code changes and use unique identifiers
- Reduced test failures from 31 to 6 (improved test coverage)

### Changed
- Enhanced test suite with better data isolation using unique IDs
- Improved test reliability for client creation and updates

## [0.4.4] - 2026-01-08

### Changed
- Update lang

## [0.4.3] - 2026-01-08

### Added
- **Project Category Master Data Management**: Complete CRUD interface for managing project categories
  - Enhanced ProjectCategory Livewire component with full management capabilities
  - New blade view with comprehensive UI for category operations
  - Added translation keys for project category labels

### Changed
- **Document Services Enhancement**: Extended document service classes with additional methods
  - Updated all 15 document service implementations (Proposal, SPH, SPK, SOW, TOR, etc.)
  - Enhanced Base document service with improved functionality
  - Added new methods to BackupRequest, Bast, BeritaAcara, Blueprint, ChangeRequest, Invoice, Panduan, Receipt, Uat, and UseCase services

### Improved
- **Versioned Document View**: Enhanced UI/UX for document version management
  - Better visual layout and organization
  - Improved user interaction patterns
- **Project Model**: Added new functionality to Project model
- **ProjectDocument Model**: Extended capabilities with new methods
- **Config Component**: Refined configuration interface
  - Removed unnecessary code
  - Improved overall component structure
- **Client Category View**: Minor UI improvements

## [0.4.2] - 2026-01-08

### Fixed
- Set Source of Sales default value to WEB when empty

## [0.4.1] - 2026-01-01

### Added
- **Division Code Field**: Added code field to divisions table for custom division codes
  - New migration: `add_code_field_to_divisions_table`
  - Allows for custom identification codes for divisions
- **ResetIndex Command**: New Artisan command for resetting document index numbers
  - Helps maintain document numbering consistency
  - Can be used for administrative purposes

### Changed
- **Internationalization**: Enhanced translation coverage across all components
  - Updated division form and list translations
  - Improved config page translations
  - Better label consistency throughout the module

### Fixed
- **Code Formatting**: Fixed code style issues across multiple files using Laravel Pint
  - Fixed class definition and braces positioning in migrations
  - Fixed blank line handling in language files
  - Fixed statement formatting in command files

## [0.4.0] - 2025-12-30

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
