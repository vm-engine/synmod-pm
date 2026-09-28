<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_category_id')->constrained('project_categories')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number');
            $table->foreignId('spk_document_id')->constrained('project_documents')->cascadeOnDelete();
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('recipient');
            $table->text('address');
            $table->string('attention');
            $table->text('notes')->nullable();
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->boolean('include_vat')->default(true);
            $table->decimal('vat_percentage', 4, 2)->default(11);
            $table->decimal('vat_amount', 15, 2)->default(0.00);
            $table->boolean('include_discount')->default(false);
            $table->decimal('discount_percentage', 4, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->boolean('include_dpp')->default(false);
            $table->decimal('dpp_amount', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->decimal('paid_amount', 15, 2)->default(0.00);
            $table->foreignId('issuer_id')->constrained('document_issuers')->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->boolean('has_signature')->default(false);
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->nullOnDelete();
            $table->foreignId('project_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_invoices');
    }
};
