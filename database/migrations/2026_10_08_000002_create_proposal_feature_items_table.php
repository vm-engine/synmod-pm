<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_feature_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('default_checked')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();
        DB::table('proposal_feature_items')->insert(array_map(
            fn (array $item): array => $item + ['active' => true, 'created_at' => $now, 'updated_at' => $now],
            ProposalDefaults::featureItems(),
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_feature_items');
    }
};
