<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_spks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->unique()->constrained('projects')->cascadeOnDelete();
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('number')->nullable();
            $table->date('spk_date');
            $table->string('title', 500);
            $table->string('party1_company');
            $table->text('party1_address')->nullable();
            $table->string('party1_representative')->default('');
            $table->string('party1_position')->default('');
            $table->string('party1_description', 500)->default('');
            $table->foreignId('issuer_id')->nullable()->constrained('document_issuers')->nullOnDelete();
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->nullOnDelete();
            $table->boolean('has_signature')->default(true);
            $table->text('party2_address')->nullable();
            $table->string('party2_description', 500)->default('');
            $table->text('asas');
            $table->text('scope');
            $table->string('reference_document', 500)->default('');
            $table->unsignedSmallInteger('work_duration')->default(0);
            $table->string('work_duration_unit', 20)->default('hari kerja');
            $table->string('work_start_text', 500)->default('');
            $table->unsignedTinyInteger('maintenance_months')->default(0);
            $table->text('duration_notes')->nullable();
            $table->text('obligations_first');
            $table->text('obligations_second');
            $table->decimal('contract_value', 15, 2)->default(0);
            $table->boolean('tax_note')->default(true);
            $table->json('installments');
            $table->text('payment_notes')->nullable();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->boolean('server_enabled')->default(false);
            $table->text('server_text')->nullable();
            $table->text('confidentiality');
            $table->text('dispute');
            $table->boolean('correspondence_enabled')->default(true);
            $table->json('contacts');
            $table->text('correspondence_text')->nullable();
            $table->json('extra_pasal');
            $table->text('misc');
            $table->json('attachments');
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
        Schema::dropIfExists('project_spks');
    }
};
