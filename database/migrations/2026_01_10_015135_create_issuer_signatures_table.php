<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issuer_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issuer_id')->constrained('document_issuers')->cascadeOnDelete();
            $table->foreignId('signature_id')->constrained('signatures')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issuer_signatures');
    }
};
