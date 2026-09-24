<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateProjectUploadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mod-pm:migrate-project-uploads
                            {--dry-run : Show what would be migrated without actually migrating}
                            {--keep-original : Keep files in public disk after migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate project uploaded files from public to local storage';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $keepOriginal = $this->option('keep-original');

        if ($isDryRun) {
            $this->info('🔍 Running in DRY RUN mode - no files will be moved');
        }

        $this->info('📋 Scanning project files in public storage...');

        // Define source and destination disks
        $sourceDisk = 'public';
        $destinationDisk = 'local';
        $directoryPath = 'projects';

        // Check if source directory exists
        if (! Storage::disk($sourceDisk)->exists($directoryPath)) {
            $this->info('✅ No project files found in public storage. Nothing to migrate.');

            return self::SUCCESS;
        }

        // Get all files recursively
        $files = Storage::disk($sourceDisk)->allFiles($directoryPath);
        $totalFiles = count($files);

        if ($totalFiles === 0) {
            $this->info('✅ No files found in public storage. Nothing to migrate.');

            return self::SUCCESS;
        }

        $this->info("Found {$totalFiles} file(s) to migrate.");

        $migratedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        foreach ($files as $file) {
            $bar->advance();

            try {
                // Check if file already exists in local storage
                if (Storage::disk($destinationDisk)->exists($file)) {
                    $skippedCount++;

                    continue;
                }

                if (! $isDryRun) {
                    // Copy from public disk to local disk; put() reports whether the write succeeded
                    $fileContents = Storage::disk($sourceDisk)->get($file);
                    if ($fileContents === null || ! Storage::disk($destinationDisk)->put($file, $fileContents)) {
                        throw new \Exception('File verification failed after copy');
                    }

                    // Delete from public disk if not keeping originals
                    if (! $keepOriginal) {
                        Storage::disk($sourceDisk)->delete($file);
                    }
                    $migratedCount++;
                } else {
                    $migratedCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->newLine();
                $this->error("❌ Error migrating {$file}: ".$e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 Migration Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['Migrated', $migratedCount.($isDryRun ? ' (would be migrated)' : '')],
                ['Already in local', $skippedCount],
                ['Errors', $errorCount],
                ['Total', $totalFiles],
            ]
        );

        if ($isDryRun) {
            $this->newLine();
            $this->info('💡 Run without --dry-run to actually migrate the files.');

            if (! $keepOriginal) {
                $this->info('💡 Files will be deleted from public storage after migration.');
                $this->info('💡 Add --keep-original to keep files in public storage after migration.');
            }
        } elseif ($migratedCount > 0) {
            $this->newLine();
            $this->info("✅ Successfully migrated {$migratedCount} file(s) to local storage.");

            if ($keepOriginal) {
                $this->warn('⚠️  Original files are still in public storage.');
            } else {
                $this->info('🗑️  Original files have been deleted from public storage.');
            }
        }

        return $errorCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}
