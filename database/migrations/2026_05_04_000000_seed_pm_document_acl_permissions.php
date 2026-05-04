<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $rows = DB::table('role_permissions')
            ->where('module_slug', 'pm')
            ->where('feature_slug', 'project')
            ->whereNull('sub_feature_slug')
            ->where('can_read', true)
            ->pluck('role_id')
            ->map(fn ($roleId) => [
                'role_id' => $roleId,
                'module_slug' => 'pm',
                'feature_slug' => 'document',
                'sub_feature_slug' => null,
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows === []) {
            return;
        }

        DB::table('role_permissions')->insertOrIgnore($rows);
    }

    public function down(): void
    {
        DB::table('role_permissions')
            ->where('module_slug', 'pm')
            ->where('feature_slug', 'document')
            ->whereNull('sub_feature_slug')
            ->delete();
    }
};
