<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_type_defaults', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->unique();
            $table->json('costing');
            $table->json('hosting');
            $table->json('timeline_tasks');
            $table->json('deliverables');
            $table->json('after_sales');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();
        foreach (ProposalType::cases() as $type) {
            $defaults = ProposalDefaults::for($type);
            DB::table('proposal_type_defaults')->insert([
                'type' => $type->value,
                'costing' => json_encode($defaults['costing']),
                'hosting' => json_encode($defaults['hosting']),
                'timeline_tasks' => json_encode($defaults['timeline_tasks']),
                'deliverables' => json_encode($defaults['deliverables']),
                'after_sales' => json_encode($defaults['after_sales']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_type_defaults');
    }
};
