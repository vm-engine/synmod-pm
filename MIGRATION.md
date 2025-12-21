# Data Migration Guide

This guide explains how to migrate data from the old database to the new PM module schema.

## Prerequisites

1. Ensure all migrations are run: `php artisan migrate`
2. Configure the old database connection in your `config/database.php`

## Step 1: Configure Source Database Connection

Add a new database connection in `config/database.php`:

```php
'connections' => [
    // ... existing connections

    'old_member' => [
        'driver' => 'mysql',
        'host' => env('OLD_DB_HOST', '127.0.0.1'),
        'port' => env('OLD_DB_PORT', '3306'),
        'database' => env('OLD_DB_DATABASE', 'member4vm_member'),
        'username' => env('OLD_DB_USERNAME', 'root'),
        'password' => env('OLD_DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],
],
```

## Step 2: Add Environment Variables

Add these to your `.env` file:

```env
OLD_DB_HOST=127.0.0.1
OLD_DB_PORT=3306
OLD_DB_DATABASE=member4vm_member
OLD_DB_USERNAME=root
OLD_DB_PASSWORD=your_password
```

## Step 3: Run Migration Command

Execute the migration command:

```bash
php artisan mod-pm:migrate-old old_member
```

The command will:
1. Test the connection to the source database
2. Ask for confirmation before proceeding
3. Migrate data in this order:
   - Users
   - Divisions
   - Sub Divisions
   - Clients
   - Projects

## What Gets Migrated

### Users Table
- All user records with their authentication data
- Preserves original IDs, timestamps, and credentials

### Divisions
- Old table: `division`
- New table: `divisions`
- All division records with audit fields

### Sub Divisions
- Old table: `division_sub`
- New table: `sub_divisions`
- All sub division records linked to divisions

### Clients
- Old table: `client`
- New table: `clients`
- **Data Transformations:**
  - `services`: JSON string → JSON array
  - `social_media`: JSON string → JSON array
  - `is_proposal`: tinyint → boolean
  - `status`: Set to `1` (Active Client) for all existing clients
  - `active`: Set to `true` for all existing clients
  - `client_category_id`: Set to `null` (can be assigned later)

### Projects
- Old table: `project`
- New table: `projects`
- **Data Transformations:**
  - `sub_division`: Comma-separated string → JSON array of integers
  - Example: `"1, 2, 3"` → `[1, 2, 3]`

## Idempotency

The migration command is **idempotent**, meaning you can run it multiple times safely:

- Uses `updateOrCreate()` for all records
- Matches records by ID
- Updates existing records if they exist
- Creates new records if they don't exist
- No duplicate data will be created

## Progress Tracking

The command shows:
- Progress bars for each table
- Count of migrated records
- Count of skipped records (if any errors)
- Detailed error messages for failed records

## Example Output

```
✓ Connected to source database: old_member

This will migrate data from the old database. Continue? (yes/no) [yes]:
> yes

Starting data migration...

Migrating users...
 100/100 [============================] 100%
  → Migrated: 100, Skipped: 0

Migrating divisions...
 6/6 [============================] 100%
  → Migrated: 6, Skipped: 0

Migrating sub divisions...
 22/22 [============================] 100%
  → Migrated: 22, Skipped: 0

Migrating clients...
 534/534 [============================] 100%
  → Migrated: 534, Skipped: 0

Migrating projects...
 10/10 [============================] 100%
  → Migrated: 10, Skipped: 0

✓ Migration completed successfully!
```

## Post-Migration Tasks

After migration, you may want to:

1. **Assign Client Categories:**
   ```php
   // Set all government clients to category 1
   Client::where('company', 'LIKE', '%dinas%')->update(['client_category_id' => 1]);

   // Set specific clients
   Client::find(1)->update(['client_category_id' => 2]);
   ```

2. **Update Client Status:**
   ```php
   use VmEngine\Pm\Models\Client;

   // Mark inactive clients as prospects
   Client::where('active', false)->update(['status' => Client::STATUS_PROSPECT]);
   ```

3. **Verify Data:**
   ```bash
   # Check counts
   php artisan tinker
   >>> \VmEngine\Pm\Models\Division::count()
   >>> \VmEngine\Pm\Models\SubDivision::count()
   >>> \VmEngine\Pm\Models\Client::count()
   >>> \VmEngine\Pm\Models\Project::count()
   ```

## Troubleshooting

### Connection Error
```
✗ Failed to connect to source database: old_member
SQLSTATE[HY000] [1045] Access denied
```

**Solution:** Check your database credentials in `.env`

### Foreign Key Constraint Error
```
Failed to migrate client ID 1: SQLSTATE[23000]: Integrity constraint violation
```

**Solution:** Ensure users are migrated first (they run automatically in the correct order)

### JSON Parsing Error
```
Failed to migrate client ID 5: Invalid JSON
```

**Solution:** The command handles this gracefully and skips invalid JSON, using `null` instead

## Re-running Migration

If you need to re-run the migration:

1. The command is safe to re-run (idempotent)
2. It will update existing records with fresh data from the source
3. No duplicates will be created

## Manual Cleanup (Optional)

If you want to start fresh:

```bash
# Truncate tables (WARNING: This deletes all data!)
php artisan tinker
>>> \VmEngine\Pm\Models\Project::truncate()
>>> \VmEngine\Pm\Models\Client::truncate()
>>> \VmEngine\Pm\Models\SubDivision::truncate()
>>> \VmEngine\Pm\Models\Division::truncate()

# Then re-run migration
php artisan mod-pm:migrate-old old_member
```

## Notes

- The migration preserves all original IDs
- Timestamps are preserved from the source database
- The command shows detailed progress and error messages
- Failed records are logged but don't stop the migration
- Always backup your database before running migrations!
