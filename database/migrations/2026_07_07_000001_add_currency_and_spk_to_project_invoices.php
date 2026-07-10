<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->string('currency', 3)->default('IDR')->after('invoice_number');
            $table->boolean('is_external_spk')->default(false)->after('spk_document_id');
            $table->string('spk_number')->nullable()->after('is_external_spk');
            $table->foreignId('invoice_document_en_id')->nullable()->after('invoice_document_id')
                ->constrained('project_documents')->nullOnDelete();
            $table->foreignId('receipt_document_en_id')->nullable()->after('receipt_document_id')
                ->constrained('project_documents')->nullOnDelete();
        });

        // Make spk_document_id nullable (drop FK, re-add as nullable nullOnDelete).
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropForeign(['spk_document_id']);
        });
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->foreignId('spk_document_id')->nullable()->change();
            $table->foreign('spk_document_id')->references('id')->on('project_documents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropForeign(['spk_document_id']);
            $table->dropForeign(['invoice_document_en_id']);
            $table->dropForeign(['receipt_document_en_id']);
            $table->dropColumn([
                'currency', 'is_external_spk', 'spk_number',
                'invoice_document_en_id', 'receipt_document_en_id',
            ]);
        });
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->foreignId('spk_document_id')->nullable(false)->change();
            $table->foreign('spk_document_id')->references('id')->on('project_documents')->cascadeOnDelete();
        });
    }
};
