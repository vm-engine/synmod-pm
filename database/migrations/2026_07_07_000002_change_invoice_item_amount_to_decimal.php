<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_invoice_items', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('project_invoice_items', function (Blueprint $table) {
            $table->unsignedInteger('amount')->change();
        });
    }
};
