<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_sphs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('number')->nullable();
            $table->date('sph_date');
            $table->string('subject');
            $table->string('attachment_label', 100)->default('');
            $table->string('recipient_name');
            $table->string('recipient_title')->default('');
            $table->string('recipient_company')->default('');
            $table->text('recipient_address')->nullable();
            $table->string('need', 500);
            $table->foreignId('issuer_id')->nullable()->constrained('document_issuers')->nullOnDelete();
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->nullOnDelete();
            $table->boolean('has_signature')->default(true);
            $table->json('items');
            $table->boolean('show_duration')->default(false);
            $table->boolean('tax_note')->default(true);
            $table->boolean('payment_enabled')->default(false);
            $table->decimal('dp_percentage', 5, 2)->default(30);
            $table->json('timeline');
            $table->boolean('show_timeline_summary')->default(false);
            $table->boolean('show_timeplan')->default(false);
            $table->json('attachments');
            $table->decimal('grand_total', 15, 2)->default(0);
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
        Schema::dropIfExists('project_sphs');
    }
};
