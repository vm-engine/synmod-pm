<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->decimal('exchange_rate', 15, 6)->default(1.000000)->after('total_amount');
            $table->decimal('amount_idr', 15, 2)->default(0.00)->after('exchange_rate');
            $table->decimal('vat_amount_idr', 15, 2)->default(0.00)->after('amount_idr');
            $table->decimal('dpp_amount_idr', 15, 2)->default(0.00)->after('vat_amount_idr');
            $table->decimal('discount_amount_idr', 15, 2)->default(0.00)->after('dpp_amount_idr');
            $table->decimal('total_amount_idr', 15, 2)->default(0.00)->after('discount_amount_idr');
        });
    }

    public function down(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'exchange_rate', 'amount_idr', 'vat_amount_idr',
                'dpp_amount_idr', 'discount_amount_idr', 'total_amount_idr',
            ]);
        });
    }
};
