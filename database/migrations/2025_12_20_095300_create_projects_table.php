<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();

            // Project information
            $table->string('name');
            $table->text('description')->nullable();
            $table->year('year');

            // Sub divisions (JSON array of IDs)
            $table->json('sub_division')->nullable();

            // Client and sales information
            $table->unsignedTinyInteger('type_of_client');
            $table->unsignedInteger('pricing');
            $table->unsignedTinyInteger('source_of_sales');

            // URL and status
            $table->text('url')->nullable();
            $table->unsignedInteger('status')->default(0);

            $table->timestamps();

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();

            // Indexes for better performance
            $table->index('user_id');
            $table->index('division_id');
            $table->index('year');
            $table->index('type_of_client');
            $table->index('status');
            $table->index(['division_id', 'year']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
