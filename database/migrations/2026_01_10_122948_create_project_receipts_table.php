<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_receipts');
    }
};
