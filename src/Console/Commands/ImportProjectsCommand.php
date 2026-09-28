<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;

class ImportProjectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pm:import-projects
                            {filename : Path to the CSV file to import}
                            {--username= : Username for created_by field (defaults to first Developer user)}
                            {--division= : Division ID (defaults to first division)}
                            {--dry-run : Preview what would be imported without inserting data}';

    /**
     * Deprecated pre-3.x name, kept so existing scripts keep working.
     *
     * @var array<int, string>
     */
    protected $aliases = ['mod-pm:import-projects'];

    /**
     * The console command description.
     */
    protected $description = 'Import projects from CSV file into projects table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filename = $this->argument('filename');

        // Validate file exists
        if (! file_exists($filename)) {
            $this->error("File not found: {$filename}");

            return self::FAILURE;
        }

        // Get user for created_by
        $username = $this->option('username');
        $user = $this->getCreatorUser($username);

        if (! $user) {
            $this->error($username ? "User with username '{$username}' not found." : 'No Developer user found.');

            return self::FAILURE;
        }

        // Get default division
        $divisionId = $this->option('division');
        $defaultDivision = $divisionId
            ? Division::find($divisionId)
            : Division::query()->oldest('id')->first();

        if (! $defaultDivision) {
            $this->error($divisionId ? "Division with ID {$divisionId} not found." : 'No divisions found in the database.');

            return self::FAILURE;
        }

        $isDryRun = $this->option('dry-run');

        $this->info("Using creator: {$user->username} (ID: {$user->id})");
        $this->info("Default division: {$defaultDivision->name} (ID: {$defaultDivision->id})");
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No data will be inserted');
        }
        $this->newLine();

        // Parse CSV
        $handle = fopen($filename, 'r');
        if (! $handle) {
            $this->error("Failed to open file: {$filename}");

            return self::FAILURE;
        }

        // Read header
        $header = fgetcsv($handle);
        if (! $header) {
            $this->error('Failed to read CSV header.');
            fclose($handle);

            return self::FAILURE;
        }

        // Remove BOM from first column if present
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
        }

        // Find column indexes
        $columns = $this->mapColumns($header);
        if (! $columns) {
            fclose($handle);

            return self::FAILURE;
        }

        // Process rows
        $progressBar = null;
        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        $progressBar = $this->output->createProgressBar(count($rows));
        $progressBar->start();

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $previewSamples = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because: +1 for header, +1 for 1-based indexing

            try {
                $result = $this->processRow($row, $columns, $user->id, $defaultDivision->id, $isDryRun);

                if ($result === true || is_array($result)) {
                    $imported++;
                    // Store first 5 samples for dry-run preview
                    if ($isDryRun && count($previewSamples) < 5 && is_array($result)) {
                        $previewSamples[] = $result;
                    }
                } else {
                    $skipped++;
                    $errors[] = "Row {$rowNumber}: {$result}";
                }
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Row {$rowNumber}: {$e->getMessage()}";
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        if ($isDryRun) {
            $this->info('✓ Dry run completed!');
            $this->info("  Would import: {$imported}");
            $this->info("  Would skip:   {$skipped}");

            // Show preview of first few records
            if (! empty($previewSamples)) {
                $this->newLine();
                $this->info('Preview of first '.count($previewSamples).' records that would be imported:');
                $this->newLine();

                foreach ($previewSamples as $index => $sample) {
                    $this->line('  Record #'.($index + 1).':');
                    $this->line("    Client: {$sample['client_name']} (ID: {$sample['client_id']})");
                    $this->line("    Project: {$sample['name']}");
                    $this->line("    Category: {$sample['category_name']} ({$sample['category_code']})");
                    $this->line("    Division: {$sample['division_name']}");
                    $this->line("    Year: {$sample['year']}");
                    $this->line('    Status: '.ProjectStatus::PROSPECT->label());
                    $this->line('    Priority: '.ProjectPriority::MEDIUM->label());
                    $this->line("    Client PIC: {$sample['client_pic']}");
                    $this->line("    Client Phone: {$sample['client_pic_phone']}");
                    $this->newLine();
                }
            }
        } else {
            $this->info('✓ Import completed!');
            $this->info("  Imported: {$imported}");
            $this->info("  Skipped:  {$skipped}");
        }

        if (! empty($errors)) {
            $this->newLine();
            $this->warn('Errors encountered:');
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Get the user to use for created_by field.
     */
    protected function getCreatorUser(?string $username): mixed
    {
        $UserModel = config('auth.providers.users.model');

        if ($username === 'regy4vm') {
            $user = $UserModel::where('username', 'regy4vm')->first();
            if ($user) {
                return $user;
            }
        }

        if ($username) {
            return $UserModel::where('username', $username)->first();
        }

        // Get first Developer user (role_id = 1)
        return $UserModel::query()
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', 1)
            ->select('users.*')
            ->oldest('users.id')
            ->first();
    }

    /**
     * Map CSV columns to expected positions.
     *
     * @param  array<int, string|null>  $header
     * @return array<string, int>|null
     */
    protected function mapColumns(array $header): ?array
    {
        $requiredColumns = [
            'Recepient' => null,
            'Project Name' => null,
            'Project Categories' => null,
        ];

        $optionalColumns = [
            'Division' => null,
        ];

        $allColumns = array_merge($requiredColumns, $optionalColumns);

        foreach ($header as $index => $column) {
            // Remove BOM, trim whitespace and normalize
            $column = preg_replace('/^\x{FEFF}/u', '', $column);
            $column = trim($column);

            if (array_key_exists($column, $allColumns)) {
                $allColumns[$column] = $index;
            }
        }

        // Check for missing required columns only
        $missing = array_filter($requiredColumns, function ($index) use ($allColumns) {
            return $allColumns[$index] === null;
        }, ARRAY_FILTER_USE_KEY);

        if (! empty($missing)) {
            $this->error('Missing required columns: '.implode(', ', array_keys($missing)));

            return null;
        }

        return $allColumns;
    }

    /**
     * Process a single CSV row.
     *
     * @param  array<int, string|null>  $row
     * @param  array<string, int>  $columns
     * @return bool|string|array<string, mixed>
     */
    protected function processRow(array $row, array $columns, int $createdBy, int $defaultDivisionId, bool $isDryRun = false): bool|string|array
    {
        $clientName = trim($row[$columns['Recepient']] ?? '');
        $projectName = trim($row[$columns['Project Name']] ?? '');
        $categoryCode = trim($row[$columns['Project Categories']] ?? '');
        $divisionName = isset($columns['Division'])
            ? trim($row[$columns['Division']] ?? '')
            : '';

        // Validate required fields
        if (empty($clientName)) {
            return 'Empty client name';
        }

        if (empty($projectName)) {
            return 'Empty project name';
        }

        if (empty($categoryCode)) {
            return 'Empty project category';
        }

        // Find client by company name
        $client = Client::where('company', $clientName)->first();
        if (! $client) {
            return "Client not found: {$clientName}";
        }

        // Find project category by code
        $category = ProjectCategory::where('code', strtoupper($categoryCode))->first();
        if (! $category) {
            return "Project category not found: {$categoryCode}";
        }

        // Find division by name or use default
        $division = null;
        if (! empty($divisionName)) {
            $division = Division::where('name', $divisionName)->first();
        }

        if (! $division) {
            $division = Division::find($defaultDivisionId);
        }

        if (! $division) {
            return 'Division not found';
        }

        $projectData = [
            'client_id' => $client->id,
            'client_name' => $client->company,
            'user_id' => $client->user_id,
            'name' => $projectName,
            'project_category_id' => $category->id,
            'category_name' => $category->name,
            'category_code' => $category->code,
            'division_id' => $division->id,
            'division_name' => $division->name,
            'year' => 2026,
            'status' => ProjectStatus::PROSPECT,
            'priority' => ProjectPriority::MEDIUM,
            'client_pic' => $client->pic,
            'client_pic_phone' => $client->phone,
            'source_of_sales' => 1,
            'created_by' => $createdBy,
            'updated_by' => $createdBy,
        ];

        // Create project (skip if dry-run)
        if (! $isDryRun) {
            Project::create($projectData);

            return true;
        }

        return $projectData;
    }
}
