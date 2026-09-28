<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

test('module can be discovered', function () {
    $modulePath = base_path('vendor/vm-engine/synmod-pm');

    if (! File::exists($modulePath)) {
        $modulePath = base_path('packages/synmod-pm');
    }

    if (! File::exists($modulePath)) {
        $modulePath = base_path('synapps/modules/pm');
    }

    expect($modulePath)->toBeDirectory();

    $moduleJson = $modulePath.'/module.json';
    expect($moduleJson)->toBeFile();

    $config = json_decode(File::get($moduleJson), true);
    expect($config)->toHaveKey('id');
    expect($config['id'])->toBe('pm');
});
