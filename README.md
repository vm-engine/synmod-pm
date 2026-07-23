# Project Management Module

A full-featured Project Management module for Laravel Synapse applications.

## Installation

For package modules:

```bash
composer require vm-engine/synmod-pm
php artisan synapps:discover
php artisan config:cache
php artisan migrate
```

For internal modules, the module is auto-discovered.

## Features

### Master Data Management
- **Divisions**: Organizational divisions with sub-divisions and hierarchy display
- **Sub Divisions**: Subdivisions within divisions
- **Client Categories**: Client categorization system
- **Project Categories**: Project categorization with document index support

### Client Management
- **Client CRUD**: Complete client management with auto user account creation
- **Client Categories**: Organize clients by categories
- **Services Tracking**: Multi-select service assignments (Design, Development, SEO, etc.)
- **Status Management**: Prospect vs Active client status with visual indicators
- **Advanced Filtering**: Filter by category, services, status, and active state with session-based state persistence (navigating back restores filters)
- **User Integration**: Auto-creates user accounts with role assignment on client creation
- **Active Status Sync**: Client active status syncs with user account status
- **Business Logic**:
  - Prospect clients automatically have inactive user accounts
  - Active clients with active flag enabled have active user accounts
  - Email verification is automatic for all client users

### Project Management
- **Project CRUD**: Full project management linked to clients and divisions
- **Document Management**: Upload and track project documents (PROPOSAL, SPH, SPK, INVOICE)
  - File upload bug fixed for Livewire v4 (metadata collected before `store()`)
- **Sub-Division Assignment**: JSON-based sub-division array for flexible assignment

### Invoice Management
- **Invoice CRUD**: Full invoice management with receipt tracking
- **PDF Generation**: Generate invoice PDFs and receipts from modal without auto-close
- **Invoice List with Refresh**: Invoice list updates automatically when invoice detail or receipt modal is closed
- **Excel Export**: Background job Excel export with persistent reference tracking
  - Three UI states: processing (with polling), completed (with download button), idle
  - Automatically deletes previous export when regenerating
- **Report Pages**: Invoice report and project document report with visual enhancements
  - Color-coded document type badges
  - Monthly/yearly breakdowns
  - Improved empty states

### Invoice REST API
Five protected API endpoints for external integrations:

| Endpoint | Description |
|----------|-------------|
| `GET /api/pm/invoice/statistics` | Invoice statistics summary (totals, paid/unpaid breakdown) |
| `GET /api/pm/invoice/by-creator` | Invoices grouped by creator (optional creator ID filter) |
| `GET /api/pm/invoice/by-project-category` | Invoices grouped by project category |
| `GET /api/pm/invoice/by-month` | Monthly invoice breakdown with paid/unpaid metrics |
| `GET /api/pm/invoice/list` | Paginated invoice list with filters and sorting |

All endpoints require authentication via `auth:sanctum` and the `pm.report.invoice.read` permission. Supports two authentication methods:
- **Permanent API tokens** (H2H): `Authorization: Bearer {permanent_token}`
- **Login flow**: Standard session/token login then use access token

A Postman collection (`postman/PM_Invoice_API.postman_collection.json`) and environment (`postman/PM_Invoice_API.postman_environment.json`) are included for easy testing.

### Client REST API
Read-only API endpoints for external integrations needing client data and its related lookups:

| Endpoint | Description |
|----------|--------------|
| `GET /api/pm/client/list` | Paginated client list with filters (category, status, active, services, search) and sorting |
| `GET /api/pm/client/{id}` | Client detail, including resolved category and service labels |
| `GET /api/pm/client-category/list` | Paginated client category list with name search |
| `GET /api/pm/service/list` | Static list of all available services (id + label), backed by the `Services` enum |

**List filters (`/client/list`):**

| Parameter | Description |
|-----------|--------------|
| `client_category_id` | Filter by client category ID |
| `status` | `0` = prospect, `1` = active |
| `active` | Boolean — client's active flag |
| `services[]` | Array of service IDs — any-match |
| `q` | Search across company, address, city, director, pic |
| `sort_by` | `company`, `status`, `active`, or `created_at` (default: `created_at`) |
| `sort_desc` | `1` = descending (default), `0` = ascending |
| `page`, `limit` | Pagination (`limit` max 100, default 20) |

All endpoints require authentication via `auth:sanctum` and the `pm.client.read` permission. Supports the same two authentication methods as the Invoice API (permanent H2H tokens or login flow).

A Postman collection (`postman/PM_Client_API.postman_collection.json`) and environment (`postman/PM_Client_API.postman_environment.json`) are included for easy testing. A standalone smoke-test script is also available at `scripts/test-client-api.php` (plain PHP + `ext-curl`, no framework bootstrap required) — authenticate with a permanent API token (Auth > API Users) and run it against a live instance with:

```bash
php scripts/test-client-api.php '1|your-permanent-token' http://localhost:8000
```

### API Request Logging
- All API requests/responses are logged via the `pm.api.logger` terminable middleware
- Logs stored in `api_logs` table: user, method, URL, route, headers, input, IP, user agent, status code, response body, duration
- Authorization tokens masked in stored headers
- Response body truncated at 64KB

### Session-Based State Persistence
List components remember their filter/sort/pagination state in the session via the `RemembersQueryParams` trait (Synapse v2.1+). Navigating back from a detail view restores the previous list state automatically. URL parameters always take precedence, so shared links still work correctly.

Applies to: Client list, User list, and all other list/table components.

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

See [MIGRATION.md](MIGRATION.md) for detailed migration guide and troubleshooting.

## Requirements

- Laravel 12+
- Livewire v4
- vm-engine/synapse ^2.0
- vm-engine/synapps-auth ^2.0

## License

MIT

## Author

**Achmad Ardiansyah**
Email: theadods@gmail.com

## Version

0.8.1
