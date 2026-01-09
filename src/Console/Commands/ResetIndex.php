<?php

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\ProjectCategory;

class ResetIndex extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mod-pm:reset-index';

    /**
     * The console command description.
     */
    protected $description = 'Reset Document Index';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $categories = ProjectCategory::all();
        foreach ($categories as $category) {
            foreach (Documents::cases() as $document) {
                dbconf([
                    'pm.documents.'.$category->code.'.'.$document->code().'.next_c_index' => 1,
                ]);
                if ($document->hasNCIndex()) {
                    dbconf([
                        'pm.documents.'.$category->code.'.'.$document->code().'.next_nc_index' => 1,
                    ]);
                }
            }
        }
        $this->components->info('Document index has beenreset.');

        return 0;
    }
}
