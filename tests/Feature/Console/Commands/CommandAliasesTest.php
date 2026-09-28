<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('keeps the deprecated mod-pm: name as an alias', function (string $name) {
    $commands = Artisan::all();

    expect($commands)->toHaveKeys(["pm:{$name}", "mod-pm:{$name}"])
        ->and($commands["mod-pm:{$name}"])->toBe($commands["pm:{$name}"]);
})->with([
    'reset-index',
    'sync-client-user-status',
    'recalculate-invoice-conversions',
    'import-projects',
    'migrate-old',
    'migrate-project-uploads',
]);
