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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('client_category_id')->nullable()->constrained('client_categories')->nullOnDelete();

            // Company information
            $table->string('company')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();

            // Contact information
            $table->string('director')->nullable();
            $table->string('pic')->nullable();
            $table->string('phone')->nullable();

            // Services and social media (JSON)
            $table->json('services')->nullable();
            $table->json('social_media')->nullable();

            // Status fields
            $table->unsignedTinyInteger('status')->default(0)->comment('0=Prospect Client, 1=Active Client');
            $table->boolean('active')->default(true);
            $table->boolean('is_proposal')->default(false);

            $table->timestamps();

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();

            // Indexes
            $table->index('user_id');
            $table->index('client_category_id');
            $table->index('company');
            $table->index('city');
            $table->index('status');
            $table->index('active');
            $table->index('is_proposal');
            $table->index(['status', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
