<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use VmEngine\Pm\Models\Client;

class SyncClientUserStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mod-pm:sync-client-user-status
                            {--force : Force update all users regardless of current status}
                            {--show-details : Show detailed information for each client}';

    /**
     * The console command description.
     */
    protected $description = 'Sync all client user active status and name based on client data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting client user status synchronization...');
        $this->newLine();

        $clients = Client::with('user')->get();
        $totalClients = $clients->count();

        if ($totalClients === 0) {
            $this->warn('No clients found.');

            return self::SUCCESS;
        }

        $this->info("Found {$totalClients} clients to process.");
        $this->newLine();

        $force = $this->option('force');
        $showDetails = $this->option('show-details');

        if ($force) {
            $this->warn('Force mode enabled - all users will be updated.');
            $this->newLine();
        }

        $progressBar = ! $showDetails ? $this->output->createProgressBar($totalClients) : null;
        $progressBar?->start();

        $updated = 0;
        $skipped = 0;
        $namesSynced = 0;
        $errors = 0;

        foreach ($clients as $client) {
            try {
                // Calculate the correct user active status
                // User should be active only if client status is ACTIVE (1) AND client is active
                $shouldBeActive = $client->status === Client::STATUS_ACTIVE && $client->active;
                $currentlyActive = (bool) $client->user->status;
                $needsNameSync = empty($client->user->name);

                $statusText = $client->status === Client::STATUS_PROSPECT ? 'PROSPECT' : 'ACTIVE';

                if ($showDetails) {
                    $this->line(sprintf(
                        'Client #%d (%s) | Status: %s | Client Active: %s | User Active: %s -> %s%s',
                        $client->id,
                        $client->company,
                        $statusText,
                        $client->active ? 'Yes' : 'No',
                        $currentlyActive ? 'Yes' : 'No',
                        $shouldBeActive ? 'Yes' : 'No',
                        $needsNameSync ? ' | Name: Empty' : ''
                    ));
                }

                $dataToUpdate = [];
                $statusNeedsUpdate = $force || $shouldBeActive !== $currentlyActive;

                if ($statusNeedsUpdate) {
                    $dataToUpdate['status'] = $shouldBeActive;
                }

                if ($needsNameSync) {
                    $dataToUpdate['name'] = $client->company;
                    $namesSynced++;
                }

                if (! empty($dataToUpdate)) {
                    $client->user->update($dataToUpdate);
                    $updated++;

                    if ($showDetails) {
                        $updates = [];
                        if (isset($dataToUpdate['status'])) {
                            $updates[] = 'status';
                        }
                        if (isset($dataToUpdate['name'])) {
                            $updates[] = 'name';
                        }
                        $this->info('  ✓ Updated user #'.$client->user->id.' ('.implode(', ', $updates).')');
                    }
                } else {
                    $skipped++;

                    if ($showDetails) {
                        $this->comment('  - Already correct');
                    }
                }
            } catch (\Exception $e) {
                $errors++;
                if (! $showDetails) {
                    $this->newLine();
                }
                $this->error("Error updating user for client ID {$client->id}: {$e->getMessage()}");
            }

            $progressBar?->advance();
        }

        $progressBar?->finish();
        if (! $showDetails) {
            $this->newLine(2);
        } else {
            $this->newLine();
        }

        // Display summary
        $this->info('Synchronization completed!');
        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                ['Updated', $updated],
                ['Names synced', $namesSynced],
                ['Already correct', $skipped],
                ['Errors', $errors],
                ['Total processed', $totalClients],
            ]
        );

        return self::SUCCESS;
    }
}
