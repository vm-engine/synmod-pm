<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->boolean('archived')->default(false)->after('is_paid');

            $table->index('archived');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('npwp_image')->nullable()->after('social_media');
        });
    }

    public function down(): void
    {
        Schema::table('project_invoices', function (Blueprint $table) {
            $table->dropColumn('archived');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('npwp_image');
        });
    }
};
