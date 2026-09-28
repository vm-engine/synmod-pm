<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('project_receipts');

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->renameColumn('project_document_id', 'invoice_document_id');
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->after('invoice_document_id');
            $table->text('receipt_amount_text')->nullable()->after('receipt_number');
            $table->foreignId('receipt_document_id')->nullable()->after('receipt_amount_text')->constrained('project_documents')->nullOnDelete();
            $table->timestamp('paid_at')->nullable()->after('paid_amount');
            $table->text('payment_notes')->nullable()->after('paid_at');
            $table->string('payment_proof')->nullable()->after('payment_notes');
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('paid_amount');
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }

    public function down(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->decimal('paid_amount', 15, 2)->default(0.00)->after('total_amount');
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropColumn('is_paid');
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropForeign(['receipt_document_id']);
            $table->dropColumn(['receipt_number', 'receipt_amount_text', 'receipt_document_id', 'paid_at', 'payment_notes', 'payment_proof']);
        });

        Schema::table('project_invoices', function (Blueprint $table) {
            $table->renameColumn('invoice_document_id', 'project_document_id');
        });

        Schema::create('project_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_invoice_id')->constrained('project_invoices')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('from');
            $table->string('receipt_number');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->text('amount_text')->nullable();
            $table->text('notes')->nullable();
            $table->date('issue_date');
            $table->boolean('include_vat')->default(true);
            $table->decimal('vat_amount', 15, 2)->nullable();
            $table->boolean('include_discount')->default(false);
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->boolean('include_dpp')->default(false);
            $table->decimal('dpp_amount', 15, 2)->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('payment_notes')->nullable();
            $table->foreignId('project_document_id')->nullable()->constrained('project_documents')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
};
