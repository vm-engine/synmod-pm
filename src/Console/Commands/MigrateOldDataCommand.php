<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\SubDivision;
use VmEngine\Pm\Seeders\ClientRoleSeeder;
use VmEngine\SynAuth\Models\Role;

class MigrateOldDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mod-pm:migrate-old
                            {source : Source database connection name}
                            {--skip-missing : Skip records with missing foreign key references}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate data from old database to current PM module schema';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sourceConnection = $this->argument('source');

        // Test source connection
        try {
            DB::connection($sourceConnection)->getPdo();
            $this->info("✓ Connected to source database: {$sourceConnection}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to connect to source database: {$sourceConnection}");
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        // Confirm before proceeding
        if (! $this->confirm('This will migrate data from the old database. Continue?', true)) {
            $this->info('Migration cancelled.');

            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Starting data migration...');
        $this->newLine();

        // Migrate in order of dependencies
        $this->migrateUsers($sourceConnection);
        $this->migrateDivisions($sourceConnection);
        $this->migrateSubDivisions($sourceConnection);
        $this->migrateClients($sourceConnection);
        $this->assignClientRoles();
        $this->migrateProjects($sourceConnection);

        $this->newLine();
        $this->info('✓ Migration completed successfully!');

        return self::SUCCESS;
    }

    /**
     * Migrate users from old database.
     */
    protected function migrateUsers(string $sourceConnection): void
    {
        $this->info('Migrating users...');

        $users = DB::connection($sourceConnection)
            ->table('users')
            ->get();

        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($users as $oldUser) {
            try {
                $exists = DB::table('users')->where('id', $oldUser->id)->exists();

                $userData = [
                    'name' => $oldUser->name,
                    'email' => $oldUser->email,
                    'username' => $oldUser->username ?? null,
                    'avatar' => $oldUser->photo ?? null,
                    'email_verified_at' => $oldUser->email_verified_at,
                    'status' => $oldUser->active ? 1 : 0,
                    'status_changed_at' => $oldUser->active_at ?? null,
                    'password' => $oldUser->password,
                    'remember_token' => $oldUser->remember_token ?? null,
                    'created_at' => $oldUser->created_at,
                    'updated_at' => $oldUser->updated_at,
                ];

                if ($exists) {
                    DB::table('users')->where('id', $oldUser->id)->update($userData);
                } else {
                    $userData['id'] = $oldUser->id;
                    DB::table('users')->insert($userData);
                }

                $migrated++;
            } catch (\Exception $e) {
                $this->error("  Failed to migrate user ID {$oldUser->id}: {$e->getMessage()}");
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Migrated: {$migrated}, Skipped: {$skipped}");
        $this->newLine();
    }

    /**
     * Migrate divisions from old database.
     */
    protected function migrateDivisions(string $sourceConnection): void
    {
        $this->info('Migrating divisions...');

        $divisions = DB::connection($sourceConnection)
            ->table('division')
            ->get();

        $progressBar = $this->output->createProgressBar($divisions->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($divisions as $oldDivision) {
            try {
                Division::updateOrCreate(
                    ['id' => $oldDivision->id],
                    [
                        'name' => $oldDivision->name,
                        'created_at' => $oldDivision->created_at,
                        'updated_at' => $oldDivision->updated_at,
                        'created_by' => $oldDivision->created_by,
                        'updated_by' => $oldDivision->updated_by,
                    ]
                );
                $migrated++;
            } catch (\Exception $e) {
                $this->error("Failed to migrate division ID {$oldDivision->id}: {$e->getMessage()}");
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Migrated: {$migrated}, Skipped: {$skipped}");
        $this->newLine();
    }

    /**
     * Migrate sub divisions from old database.
     */
    protected function migrateSubDivisions(string $sourceConnection): void
    {
        $this->info('Migrating sub divisions...');

        $subDivisions = DB::connection($sourceConnection)
            ->table('division_sub')
            ->get();

        $progressBar = $this->output->createProgressBar($subDivisions->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($subDivisions as $oldSubDivision) {
            try {
                SubDivision::updateOrCreate(
                    ['id' => $oldSubDivision->id],
                    [
                        'division_id' => $oldSubDivision->division_id,
                        'name' => $oldSubDivision->name,
                        'created_at' => $oldSubDivision->created_at,
                        'updated_at' => $oldSubDivision->updated_at,
                        'created_by' => $oldSubDivision->created_by,
                        'updated_by' => $oldSubDivision->updated_by,
                    ]
                );
                $migrated++;
            } catch (\Exception $e) {
                $this->error("Failed to migrate sub division ID {$oldSubDivision->id}: {$e->getMessage()}");
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Migrated: {$migrated}, Skipped: {$skipped}");
        $this->newLine();
    }

    /**
     * Migrate clients from old database.
     */
    protected function migrateClients(string $sourceConnection): void
    {
        $this->info('Migrating clients...');

        $clients = DB::connection($sourceConnection)
            ->table('client')
            ->get();

        $progressBar = $this->output->createProgressBar($clients->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;
        $skipReasons = [];

        foreach ($clients as $oldClient) {
            try {
                // Check if user exists
                $userExists = DB::table('users')->where('id', $oldClient->user_id)->first();

                if (! $userExists) {
                    if ($this->option('skip-missing')) {
                        $skipReasons['missing_user'][] = $oldClient->id;
                        $skipped++;
                        $progressBar->advance();

                        continue;
                    }

                    $this->warn("  User ID {$oldClient->user_id} not found for client {$oldClient->id}");
                }

                // Check if created_by user exists
                $createdByExists = ! $oldClient->created_by || DB::table('users')->where('id', $oldClient->created_by)->exists();
                $updatedByExists = ! $oldClient->updated_by || DB::table('users')->where('id', $oldClient->updated_by)->exists();

                // Parse JSON fields from old database
                $services = $this->parseJson($oldClient->services);
                $socialMedia = $this->parseJson($oldClient->social_media);

                Client::updateOrCreate(
                    ['id' => $oldClient->id],
                    [
                        'user_id' => $userExists ? $oldClient->user_id : 1, // Fallback to user 1
                        'client_category_id' => null, // No category in old data
                        'company' => $oldClient->company,
                        'address' => $oldClient->address,
                        'city' => $oldClient->city,
                        'director' => $oldClient->director,
                        'pic' => $oldClient->pic,
                        'phone' => $oldClient->phone,
                        'services' => $services,
                        'social_media' => $socialMedia,
                        'status' => $userExists->status ? Client::STATUS_ACTIVE : Client::STATUS_PROSPECT, // Default to active for existing clients
                        'active' => (bool) $userExists->status,
                        'is_proposal' => (bool) $oldClient->is_proposal,
                        'created_at' => $oldClient->created_at,
                        'updated_at' => $oldClient->updated_at,
                        'created_by' => $createdByExists ? $oldClient->created_by : 1,
                        'updated_by' => $updatedByExists ? $oldClient->updated_by : null,
                    ]
                );
                $migrated++;
            } catch (\Exception $e) {
                $this->error("  Failed to migrate client ID {$oldClient->id}: {$e->getMessage()}");
                $skipReasons['error'][] = $oldClient->id;
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Migrated: {$migrated}, Skipped: {$skipped}");

        if (! empty($skipReasons)) {
            foreach ($skipReasons as $reason => $ids) {
                $this->warn("  → Skipped due to {$reason}: ".implode(', ', array_slice($ids, 0, 10)).(count($ids) > 10 ? '... ('.count($ids).' total)' : ''));
            }
        }

        $this->newLine();
    }

    /**
     * Migrate projects from old database.
     */
    protected function migrateProjects(string $sourceConnection): void
    {
        $this->info('Migrating projects...');

        $projects = DB::connection($sourceConnection)
            ->table('project')
            ->get();

        $progressBar = $this->output->createProgressBar($projects->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;
        $skipReasons = [];

        foreach ($projects as $oldProject) {
            try {
                // Check if user exists
                $userExists = DB::table('users')->where('id', $oldProject->user_id)->exists();
                $divisionExists = Division::where('id', $oldProject->division_id)->exists();

                if (! $userExists || ! $divisionExists) {
                    if ($this->option('skip-missing')) {
                        $reason = ! $userExists ? 'missing_user' : 'missing_division';
                        $skipReasons[$reason][] = $oldProject->id;
                        $skipped++;
                        $progressBar->advance();

                        continue;
                    }

                    if (! $userExists) {
                        $this->warn("  User ID {$oldProject->user_id} not found for project {$oldProject->id}");
                    }

                    if (! $divisionExists) {
                        $this->warn("  Division ID {$oldProject->division_id} not found for project {$oldProject->id}");
                    }
                }

                // Check if created_by and updated_by users exist
                $createdByExists = ! $oldProject->created_by || DB::table('users')->where('id', $oldProject->created_by)->exists();
                $updatedByExists = ! $oldProject->updated_by || DB::table('users')->where('id', $oldProject->updated_by)->exists();

                // Parse sub_division field (comma-separated string to array)
                $subDivision = $this->parseSubDivision($oldProject->sub_division);

                // Map old status to new ProjectStatus enum
                $newStatus = $this->mapProjectStatus($oldProject->status);

                Project::updateOrCreate(
                    ['id' => $oldProject->id],
                    [
                        'user_id' => $userExists ? $oldProject->user_id : 1,
                        'division_id' => $divisionExists ? $oldProject->division_id : 1,
                        'name' => $oldProject->name,
                        'description' => $oldProject->description,
                        'year' => $oldProject->year,
                        'sub_division' => $subDivision,
                        'type_of_client' => $oldProject->type_of_client,
                        'pricing' => $oldProject->pricing,
                        'source_of_sales' => $oldProject->source_of_sales,
                        'url' => $oldProject->url,
                        'status' => $newStatus,
                        'created_at' => $oldProject->created_at,
                        'updated_at' => $oldProject->updated_at,
                        'created_by' => $createdByExists ? $oldProject->created_by : 1,
                        'updated_by' => $updatedByExists ? $oldProject->updated_by : null,
                    ]
                );
                $migrated++;
            } catch (\Exception $e) {
                $this->error("  Failed to migrate project ID {$oldProject->id}: {$e->getMessage()}");
                $skipReasons['error'][] = $oldProject->id;
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Migrated: {$migrated}, Skipped: {$skipped}");

        if (! empty($skipReasons)) {
            foreach ($skipReasons as $reason => $ids) {
                $this->warn("  → Skipped due to {$reason}: ".implode(', ', array_slice($ids, 0, 10)).(count($ids) > 10 ? '... ('.count($ids).' total)' : ''));
            }
        }

        $this->newLine();
    }

    /**
     * Parse JSON string from old database.
     */
    protected function parseJson(?string $json): ?array
    {
        if (empty($json)) {
            return null;
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Assign 'client' role to users who have client records.
     */
    protected function assignClientRoles(): void
    {
        $this->info('Assigning client roles to users...');

        // Check if client role exists, if not run the seeder
        $clientRole = Role::where('slug', 'client')->first();

        if (! $clientRole) {
            $this->warn('  Client role not found, running ClientRoleSeeder...');
            $this->call(ClientRoleSeeder::class);
            $clientRole = Role::where('slug', 'client')->first();

            if (! $clientRole) {
                $this->error('  Failed to create client role. Aborting role assignment.');

                return;
            }

            $this->info('  ✓ Client role created successfully');
        }

        $clientRoleId = $clientRole->id;

        // Get all unique user IDs from clients table
        $clientUserIds = Client::pluck('user_id')->unique();

        $progressBar = $this->output->createProgressBar($clientUserIds->count());
        $progressBar->start();

        $assigned = 0;
        $skipped = 0;

        foreach ($clientUserIds as $userId) {
            try {
                // Use insertOrIgnore for idempotent role assignment
                $inserted = DB::table('role_user')->insertOrIgnore([
                    'user_id' => $userId,
                    'role_id' => $clientRoleId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($inserted) {
                    $assigned++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->error("  Failed to assign client role to user ID {$userId}: {$e->getMessage()}");
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Assigned: {$assigned}, Skipped (already has role): {$skipped}");
        $this->newLine();
    }

    /**
     * Parse sub_division field (comma-separated string to array).
     */
    protected function parseSubDivision(?string $subDivision): ?array
    {
        if (empty($subDivision)) {
            return null;
        }

        // Remove spaces and split by comma
        $items = array_map('trim', explode(',', $subDivision));

        // Convert to integers and filter out empty values
        $items = array_filter(array_map('intval', $items));

        return ! empty($items) ? array_values($items) : null;
    }

    /**
     * Map old project status values to new ProjectStatus enum values.
     */
    protected function mapProjectStatus(?int $oldStatus): int
    {
        // Map old status values to new ProjectStatus enum
        // Old system values -> New enum values:
        // 0 = Prospect -> 0 (PROSPECT)
        // 1 = Planning -> 1 (PLANNING)
        // 2 = In Progress -> 2 (IN_PROGRESS)
        // 3 = On Hold -> 3 (ON_HOLD)
        // 4 = Completed -> 4 (COMPLETED)
        // 5 = Cancelled -> 5 (CANCELLED)
        // 6 = Maintenance -> 6 (MAINTENANCE)
        // 100 = Completed (legacy value) -> 4 (COMPLETED)

        return match ($oldStatus) {
            0 => 0, // PROSPECT
            1 => 1, // PLANNING
            2 => 2, // IN_PROGRESS
            3 => 3, // ON_HOLD
            4, 100 => 4, // COMPLETED (100 is legacy "completed" status)
            5 => 5, // CANCELLED
            6 => 6, // MAINTENANCE
            default => 1, // Default to PLANNING for unknown values
        };
    }
}
