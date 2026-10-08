<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('number')->nullable();
            $table->string('title');
            $table->string('subject')->default('');
            $table->date('proposal_date');
            $table->boolean('tax_note')->default(true);
            $table->unsignedTinyInteger('maintenance_months')->default(3);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->json('scope')->nullable();
            $table->json('features');
            $table->json('costing');
            $table->json('hosting');
            $table->json('timeline');
            $table->json('deliverables');
            $table->json('after_sales');
            $table->json('published_snapshot')->nullable();
            $table->unsignedInteger('published_version')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('has_unpublished_changes')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_proposals');
    }
};
