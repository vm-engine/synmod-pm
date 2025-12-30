<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\SalesSources;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
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
        $this->migrateProposalsAsProjects($sourceConnection);
        $this->migrateSphAsDocuments($sourceConnection);
        $this->migrateSpkAsDocuments($sourceConnection);

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
     * Migrate proposals from old database as projects.
     */
    protected function migrateProposalsAsProjects(string $sourceConnection): void
    {
        $this->info('Migrating proposals as projects...');

        $proposals = DB::connection($sourceConnection)
            ->table('proposal')
            ->get();

        // Get default division_id once (proposal table doesn't have division_id)
        // Use first available division or fallback to 1
        $defaultDivisionId = Division::orderBy('id')->value('id') ?? 1;

        $progressBar = $this->output->createProgressBar($proposals->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;
        $skipReasons = [];
        $documentsMigrated = 0;
        $filesMigrated = 0;
        $filesFailed = 0;

        foreach ($proposals as $proposal) {
            try {
                // Check if client exists
                $clientExists = Client::where('id', $proposal->client_id)->exists();

                if (! $clientExists) {
                    if ($this->option('skip-missing')) {
                        $skipReasons['missing_client'][] = $proposal->id;
                        $skipped++;
                        $progressBar->advance();

                        continue;
                    }

                    $this->warn("  Client ID {$proposal->client_id} not found for proposal {$proposal->id}");
                }

                // Get client to determine user_id
                $client = Client::find($proposal->client_id);
                $userId = $client ? $client->user_id : 1;

                // Map jp_id (work_type) to project_category_id
                $projectCategoryId = $this->mapWorkTypeToProjectCategory($proposal->jp_id);

                // Clean project name (remove "Proposal" or "Penawaran")
                $projectName = $this->cleanProposalName($proposal->name);

                // Extract year from proposal code (last 4 characters)
                // Fallback to created_at if year is invalid
                $year = $this->extractYearFromCode($proposal->code, $proposal->created_at);

                // Check if created_by user exists
                $createdByExists = ! $proposal->created_by || DB::table('users')->where('id', $proposal->created_by)->exists();

                // Create/update project
                $project = Project::updateOrCreate(
                    ['id' => $proposal->id],
                    [
                        'user_id' => $userId,
                        'client_id' => $clientExists ? $proposal->client_id : null,
                        'division_id' => $defaultDivisionId,
                        'project_category_id' => $projectCategoryId,
                        'name' => $projectName,
                        'year' => $year,
                        'source_of_sales' => SalesSources::WEB->value,
                        'status' => ProjectStatus::PROSPECT->value, // Default to PROSPECT status
                        'created_at' => $proposal->created_at,
                        'updated_at' => $proposal->updated_at,
                        'created_by' => $createdByExists ? $proposal->created_by : null,
                        'updated_by' => null,
                    ]
                );

                // Create proposal document for this project
                $fileInfo = $this->migrateProposalFile($proposal->file, $proposal->id);

                // Only create document if file was successfully migrated
                if ($fileInfo && $fileInfo['file_migrated'] && $fileInfo['path']) {
                    ProjectDocument::updateOrCreate(
                        [
                            'project_id' => $project->id,
                            'type' => Documents::PROPOSAL->value,
                        ],
                        [
                            'user_id' => $userId,
                            'client_id' => $proposal->client_id,
                            'number' => $proposal->code,
                            'notes' => null,
                            'file_path' => $fileInfo['path'],
                            'file_info' => $fileInfo['info'],
                            'version' => 1,
                            'created_by' => $createdByExists ? $proposal->created_by : null,
                            'created_at' => $proposal->created_at,
                            'updated_at' => $proposal->updated_at,
                        ]
                    );
                    $documentsMigrated++;
                    $filesMigrated++;
                } elseif ($fileInfo && ! $fileInfo['file_migrated']) {
                    // File migration failed or file not found
                    $filesFailed++;
                }

                $migrated++;
            } catch (\Exception $e) {
                $this->error("  Failed to migrate proposal ID {$proposal->id}: {$e->getMessage()}");
                $skipReasons['error'][] = $proposal->id;
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → Projects Migrated: {$migrated}, Skipped: {$skipped}");
        $this->line("  → Documents Created: {$documentsMigrated}");
        $this->line("  → Files Migrated: {$filesMigrated}, Failed: {$filesFailed}");

        if (! empty($skipReasons)) {
            foreach ($skipReasons as $reason => $ids) {
                $this->warn("  → Skipped due to {$reason}: ".implode(', ', array_slice($ids, 0, 10)).(count($ids) > 10 ? '... ('.count($ids).' total)' : ''));
            }
        }

        $this->newLine();
    }

    /**
     * Migrate SPH from old database as project documents.
     */
    protected function migrateSphAsDocuments(string $sourceConnection): void
    {
        $this->info('Migrating SPH as project documents...');

        $sphRecords = DB::connection($sourceConnection)
            ->table('sph')
            ->orderBy('proposal_id')
            ->orderBy('created_at')
            ->get();

        $progressBar = $this->output->createProgressBar($sphRecords->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;
        $skipReasons = [];
        $filesMigrated = 0;
        $filesFailed = 0;

        // Track version numbers per project
        $versionTracker = [];

        foreach ($sphRecords as $sph) {
            try {
                // Find the project by proposal_id
                $project = Project::find($sph->proposal_id);

                if (! $project) {
                    if ($this->option('skip-missing')) {
                        $skipReasons['missing_project'][] = $sph->id;
                        $skipped++;
                        $progressBar->advance();

                        continue;
                    }

                    $this->warn("  Project (from proposal) ID {$sph->proposal_id} not found for SPH {$sph->id}");
                }

                // Determine version number for this project
                if (! isset($versionTracker[$project->id])) {
                    // Check existing SPH documents for this project
                    $existingCount = ProjectDocument::where('project_id', $project->id)
                        ->where('type', Documents::SPH->value)
                        ->count();
                    $versionTracker[$project->id] = $existingCount + 1;
                } else {
                    $versionTracker[$project->id]++;
                }

                $version = $versionTracker[$project->id];

                // Get client from project
                $client = $project->client;
                $userId = $client ? $client->user_id : $project->user_id;

                // Clean SPH name
                $notes = $this->cleanSphName($sph->name);

                // Check if created_by user exists
                $createdByExists = ! $sph->created_by || DB::table('users')->where('id', $sph->created_by)->exists();

                // Migrate file
                $fileInfo = $this->migrateSphFile($sph->file, $sph->id, $project->id);

                // Only create document if file was successfully migrated
                if ($fileInfo && $fileInfo['file_migrated'] && $fileInfo['path']) {
                    ProjectDocument::updateOrCreate(
                        [
                            'project_id' => $project->id,
                            'type' => Documents::SPH->value,
                            'number' => $sph->code,
                        ],
                        [
                            'user_id' => $userId,
                            'client_id' => $project->client_id,
                            'notes' => $notes,
                            'file_path' => $fileInfo['path'],
                            'file_info' => $fileInfo['info'],
                            'version' => $version,
                            'created_by' => $createdByExists ? $sph->created_by : null,
                            'created_at' => $sph->created_at,
                            'updated_at' => $sph->updated_at,
                        ]
                    );
                    $migrated++;
                    $filesMigrated++;
                } elseif ($fileInfo && ! $fileInfo['file_migrated']) {
                    // File migration failed or file not found
                    $filesFailed++;
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->error("  Failed to migrate SPH ID {$sph->id}: {$e->getMessage()}");
                $skipReasons['error'][] = $sph->id;
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → SPH Documents Created: {$migrated}, Skipped: {$skipped}");
        $this->line("  → Files Migrated: {$filesMigrated}, Failed: {$filesFailed}");

        if (! empty($skipReasons)) {
            foreach ($skipReasons as $reason => $ids) {
                $this->warn("  → Skipped due to {$reason}: ".implode(', ', array_slice($ids, 0, 10)).(count($ids) > 10 ? '... ('.count($ids).' total)' : ''));
            }
        }

        $this->newLine();
    }

    /**
     * Migrate SPK from old database as project documents.
     * Also updates project and client status when is_deal = 1.
     */
    protected function migrateSpkAsDocuments(string $sourceConnection): void
    {
        $this->info('Migrating SPK as project documents...');

        $spkRecords = DB::connection($sourceConnection)
            ->table('spk')
            ->orderBy('proposal_id')
            ->orderBy('created_at')
            ->get();

        $progressBar = $this->output->createProgressBar($spkRecords->count());
        $progressBar->start();

        $migrated = 0;
        $skipped = 0;
        $skipReasons = [];
        $filesMigrated = 0;
        $filesFailed = 0;
        $projectsUpdated = 0;
        $clientsUpdated = 0;

        // Track version numbers per project
        $versionTracker = [];

        foreach ($spkRecords as $spk) {
            try {
                // Find the project by proposal_id
                $project = Project::find($spk->proposal_id);

                if (! $project) {
                    if ($this->option('skip-missing')) {
                        $skipReasons['missing_project'][] = $spk->id;
                        $skipped++;
                        $progressBar->advance();

                        continue;
                    }

                    $this->warn("  Project (from proposal) ID {$spk->proposal_id} not found for SPK {$spk->id}");
                }

                // Determine version number for this project
                if (! isset($versionTracker[$project->id])) {
                    // Check existing SPK documents for this project
                    $existingCount = ProjectDocument::where('project_id', $project->id)
                        ->where('type', Documents::SPK->value)
                        ->count();
                    $versionTracker[$project->id] = $existingCount + 1;
                } else {
                    $versionTracker[$project->id]++;
                }

                $version = $versionTracker[$project->id];

                // Get client from project
                $client = $project->client;
                $userId = $client ? $client->user_id : $project->user_id;

                // Clean SPK name
                $notes = $this->cleanSpkName($spk->name);

                // Check if created_by user exists
                $createdByExists = ! $spk->created_by || DB::table('users')->where('id', $spk->created_by)->exists();

                // Migrate file
                $fileInfo = $this->migrateSpkFile($spk->file, $spk->id, $project->id);

                // Only create document if file was successfully migrated
                if ($fileInfo && $fileInfo['file_migrated'] && $fileInfo['path']) {
                    ProjectDocument::updateOrCreate(
                        [
                            'project_id' => $project->id,
                            'type' => Documents::SPK->value,
                            'number' => $spk->code,
                        ],
                        [
                            'user_id' => $userId,
                            'client_id' => $project->client_id,
                            'notes' => $notes,
                            'file_path' => $fileInfo['path'],
                            'file_info' => $fileInfo['info'],
                            'version' => $version,
                            'created_by' => $createdByExists ? $spk->created_by : null,
                            'created_at' => $spk->created_at,
                            'updated_at' => $spk->updated_at,
                        ]
                    );
                    $migrated++;
                    $filesMigrated++;

                    // Update project and client status if is_deal = 1
                    if ($spk->is_deal == 1) {
                        // Update project status from PROSPECT to PLANNING
                        if ($project->status === ProjectStatus::PROSPECT) {
                            $project->status = ProjectStatus::PLANNING;
                            $project->save();
                            $projectsUpdated++;
                        }

                        // Update client status from PROSPECT to ACTIVE
                        if ($client && $client->status === Client::STATUS_PROSPECT) {
                            $client->status = Client::STATUS_ACTIVE;
                            $client->active = true;
                            $client->save();
                            $clientsUpdated++;
                        }
                    }
                } elseif ($fileInfo && ! $fileInfo['file_migrated']) {
                    // File migration failed or file not found
                    $filesFailed++;
                    $skipped++;
                }
            } catch (\Exception $e) {
                $this->error("  Failed to migrate SPK ID {$spk->id}: {$e->getMessage()}");
                $skipReasons['error'][] = $spk->id;
                $skipped++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->line("  → SPK Documents Created: {$migrated}, Skipped: {$skipped}");
        $this->line("  → Files Migrated: {$filesMigrated}, Failed: {$filesFailed}");
        $this->line("  → Projects Updated to PLANNING: {$projectsUpdated}");
        $this->line("  → Clients Updated to ACTIVE: {$clientsUpdated}");

        if (! empty($skipReasons)) {
            foreach ($skipReasons as $reason => $ids) {
                $this->warn("  → Skipped due to {$reason}: ".implode(', ', array_slice($ids, 0, 10)).(count($ids) > 10 ? '... ('.count($ids).' total)' : ''));
            }
        }

        $this->newLine();
    }

    /**
     * Extract year from proposal code (last 4 characters).
     * Falls back to created_at if year is invalid.
     */
    protected function extractYearFromCode(string $code, string $createdAt): int
    {
        // Get last 4 characters
        $yearString = substr($code, -4);

        // Validate it's numeric and a reasonable year (1900-2100)
        if (is_numeric($yearString)) {
            $year = (int) $yearString;

            if ($year >= 1900 && $year <= 2100) {
                return $year;
            }
        }

        // Fallback to created_at
        return (int) date('Y', strtotime($createdAt));
    }

    /**
     * Clean proposal name by removing "Proposal", "Penawaran", "Surat", "SPH", and "Harga" words.
     */
    protected function cleanProposalName(string $name): string
    {
        // Remove common proposal-related words (case insensitive)
        $wordsToRemove = [
            'PROPOSAL',
            'PENAWARAN',
            'SURAT',
            'SPH',
            'HARGA',
        ];

        // Replace each word with empty string
        foreach ($wordsToRemove as $word) {
            // Remove the word with surrounding spaces, dashes, or at boundaries
            $name = preg_replace('/\b'.preg_quote($word, '/').'\b\s*-?\s*/i', ' ', $name);
        }

        // Clean up multiple spaces and trim
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    /**
     * Clean SPH name by removing "SPH" or "SURAT PENAWARAN HARGA" prefix.
     */
    protected function cleanSphName(string $name): string
    {
        // Remove common SPH prefixes (case insensitive)
        $patterns = [
            '/^SPH\s+/i',
            '/^SPH\s*-\s*/i',
            '/^SURAT\s+PENAWARAN\s+HARGA\s+/i',
            '/^SURAT\s+PENAWARAN\s+HARGA\s*-\s*/i',
        ];

        foreach ($patterns as $pattern) {
            $name = preg_replace($pattern, '', $name);
        }

        return trim($name);
    }

    /**
     * Clean SPK name by removing "SPK" or "SURAT PERINTAH KERJA" prefix.
     */
    protected function cleanSpkName(string $name): string
    {
        // Remove common SPK prefixes (case insensitive)
        $patterns = [
            '/^SPK\s+/i',
            '/^SPK\s*-\s*/i',
            '/^SURAT\s+PERINTAH\s+KERJA\s+/i',
            '/^SURAT\s+PERINTAH\s+KERJA\s*-\s*/i',
            '/^SURAT\s+PERJANJIAN\s+KERJA\s+/i',
            '/^SURAT\s+PERJANJIAN\s+KERJA\s*-\s*/i',
            '/^SURAT\s+KOMITMEN\s+PEMBAYARAN\s+/i',
            '/^SURAT\s+KOMITMEN\s+PEMBAYARAN\s*-\s*/i',
        ];

        foreach ($patterns as $pattern) {
            $name = preg_replace($pattern, '', $name);
        }

        return trim($name);
    }

    /**
     * Map work_type ID to project_category ID.
     */
    protected function mapWorkTypeToProjectCategory(int $workTypeId): int
    {
        // Mapping based on code matching:
        // work_type.id -> project_categories.id
        return match ($workTypeId) {
            4 => 1,   // WEB -> WEB
            5 => 2,   // AW -> Web-based Application
            6 => 3,   // AWM -> Web-based Application + Mobile
            7 => 4,   // AM -> Mobile Application
            9 => 5,   // UM -> General
            10 => 6,  // MS -> Maintenance & Support
            11 => 7,  // DM -> Digital Marketing
            12 => 8,  // RHMS -> Website Redesign, Hosting, and Maintenance
            default => 5, // Default to General (UM)
        };
    }

    /**
     * Migrate proposal file from public/document/proposal to storage.
     */
    protected function migrateProposalFile(string $filename, int $proposalId): ?array
    {
        // Old file path in public/document/proposal
        $oldPath = public_path('document/proposal/'.$filename);

        // Check if old file exists
        if (! File::exists($oldPath)) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migrated_note' => 'Original file not found at: '.$oldPath,
                ],
                'file_migrated' => false,
            ];
        }

        try {
            // Get document type code (PROPOSAL code is empty, so use 0)
            $docTypeCode = Documents::PROPOSAL->code() ?: '0';

            // Generate unique filename
            $extension = File::extension($oldPath);
            $hash = Str::random(16);
            $newFilename = "{$hash}.{$extension}";

            // New path format: projects/{project_id}/documents/{document_type_code}/{filename}
            $newPath = "projects/{$proposalId}/documents/{$docTypeCode}/{$newFilename}";

            // Copy file to storage
            $fileContents = File::get($oldPath);
            Storage::put($newPath, $fileContents);

            // Get file info
            $fileSize = File::size($oldPath);
            $mimeType = File::mimeType($oldPath);

            return [
                'path' => $newPath,
                'info' => [
                    'original_name' => $filename,
                    'size' => $fileSize,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'original_path' => $oldPath,
                ],
                'file_migrated' => true,
            ];
        } catch (\Exception $e) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migration_error' => $e->getMessage(),
                ],
                'file_migrated' => false,
            ];
        }
    }

    /**
     * Migrate SPH file from public/document/sph to storage.
     */
    protected function migrateSphFile(string $filename, int $sphId, int $projectId): ?array
    {
        // Old file path in public/document/sph
        $oldPath = public_path('document/sph/'.$filename);

        // Check if old file exists
        if (! File::exists($oldPath)) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migrated_note' => 'Original file not found at: '.$oldPath,
                ],
                'file_migrated' => false,
            ];
        }

        try {
            // Get document type code
            $docTypeCode = Documents::SPH->code() ?: '0';

            // Generate unique filename
            $extension = File::extension($oldPath);
            $hash = Str::random(16);
            $newFilename = "{$hash}.{$extension}";

            // New path format: projects/{project_id}/documents/{document_type_code}/{filename}
            $newPath = "projects/{$projectId}/documents/{$docTypeCode}/{$newFilename}";

            // Copy file to storage
            $fileContents = File::get($oldPath);
            Storage::put($newPath, $fileContents);

            // Get file info
            $fileSize = File::size($oldPath);
            $mimeType = File::mimeType($oldPath);

            return [
                'path' => $newPath,
                'info' => [
                    'original_name' => $filename,
                    'size' => $fileSize,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'original_path' => $oldPath,
                ],
                'file_migrated' => true,
            ];
        } catch (\Exception $e) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migration_error' => $e->getMessage(),
                ],
                'file_migrated' => false,
            ];
        }
    }

    /**
     * Migrate SPK file from public/document/spk to storage.
     */
    protected function migrateSpkFile(string $filename, int $spkId, int $projectId): ?array
    {
        // Old file path in public/document/spk
        $oldPath = public_path('document/spk/'.$filename);

        // Check if old file exists
        if (! File::exists($oldPath)) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migrated_note' => 'Original file not found at: '.$oldPath,
                ],
                'file_migrated' => false,
            ];
        }

        try {
            // Get document type code
            $docTypeCode = Documents::SPK->code() ?: '0';

            // Generate unique filename
            $extension = File::extension($oldPath);
            $hash = Str::random(16);
            $newFilename = "{$hash}.{$extension}";

            // New path format: projects/{project_id}/documents/{document_type_code}/{filename}
            $newPath = "projects/{$projectId}/documents/{$docTypeCode}/{$newFilename}";

            // Copy file to storage
            $fileContents = File::get($oldPath);
            Storage::put($newPath, $fileContents);

            // Get file info
            $fileSize = File::size($oldPath);
            $mimeType = File::mimeType($oldPath);

            return [
                'path' => $newPath,
                'info' => [
                    'original_name' => $filename,
                    'size' => $fileSize,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'original_path' => $oldPath,
                ],
                'file_migrated' => true,
            ];
        } catch (\Exception $e) {
            return [
                'path' => null,
                'info' => [
                    'original_name' => $filename,
                    'size' => 0,
                    'mime_type' => null,
                    'migration_error' => $e->getMessage(),
                ],
                'file_migrated' => false,
            ];
        }
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
