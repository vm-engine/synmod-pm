<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn('type_of_client');

            // Rename pricing to nominal and change type to decimal
            $table->decimal('nominal', 15, 2)->nullable()->after('source_of_sales');

            // Add direct client relationship
            $table->foreignId('client_id')->nullable()->after('user_id')->constrained('clients')->nullOnDelete();

            // Add services field (JSON array from Services enum)
            $table->json('services')->nullable()->after('source_of_sales');

            // Add progress field (0-100 percentage)
            $table->unsignedTinyInteger('progress')->default(0)->after('status');

            // Add client contact information
            $table->string('client_pic')->nullable()->after('client_id');
            $table->string('client_pic_phone')->nullable()->after('client_pic');

            // Add date fields
            $table->date('project_start')->nullable()->after('description');
            $table->date('project_end')->nullable()->after('project_start');

            // Add manager field
            $table->string('manager')->nullable()->after('sub_division');

            // Add financial fields
            $table->decimal('budget', 15, 2)->nullable()->after('nominal');
            $table->decimal('down_payment', 15, 2)->nullable()->after('budget');
            $table->unsignedTinyInteger('total_terms')->default(1)->comment('Including down payment as first term')->after('down_payment');
            $table->text('payment_terms')->nullable()->after('total_terms');
            $table->timestamp('contract_signed_at')->nullable()->after('payment_terms');
            $table->timestamp('client_approved_at')->nullable()->after('contract_signed_at');

            // Add project management fields
            $table->unsignedTinyInteger('priority')->default(2)->after('progress');
            $table->unsignedTinyInteger('risk_level')->nullable()->after('priority');
            $table->foreignId('project_manager_id')->nullable()->after('manager')->constrained('users')->nullOnDelete();
            $table->json('assigned_team')->nullable()->after('project_manager_id');
            $table->json('tags')->nullable()->after('assigned_team');

            // Add schedule fields
            $table->date('planned_delivery_date')->nullable()->after('project_end');
            $table->date('actual_delivery_date')->nullable()->after('planned_delivery_date');
            $table->date('warranty_end_date')->nullable()->after('actual_delivery_date');

            // Add technical fields
            $table->text('staging_url')->nullable()->after('url');
            $table->text('production_url')->nullable()->after('staging_url');
            $table->text('repository_url')->nullable()->after('production_url');
            $table->json('server_info')->nullable()->after('repository_url');

            // Add quality fields
            $table->unsignedTinyInteger('maintenance_type')->nullable()->after('server_info');
            $table->unsignedTinyInteger('quality_score')->nullable()->after('maintenance_type');
            $table->unsignedTinyInteger('client_satisfaction')->nullable()->after('quality_score');

            // Add indexes
            $table->index('client_id');
            $table->index('project_manager_id');
            $table->index('priority');
            $table->index('project_start');
            $table->index('project_end');
            $table->index('planned_delivery_date');
            $table->index('actual_delivery_date');
        });

        // Rename pricing column to nominal after adding the new nominal column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('pricing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['client_id']);
            $table->dropForeign(['project_manager_id']);

            // Drop indexes
            $table->dropIndex(['client_id']);
            $table->dropIndex(['project_manager_id']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['project_start']);
            $table->dropIndex(['project_end']);
            $table->dropIndex(['planned_delivery_date']);
            $table->dropIndex(['actual_delivery_date']);

            // Drop all new columns
            $table->dropColumn([
                'client_id',
                'services',
                'progress',
                'client_pic',
                'client_pic_phone',
                'project_start',
                'project_end',
                'manager',
                'nominal',
                'budget',
                'down_payment',
                'total_terms',
                'payment_terms',
                'contract_signed_at',
                'client_approved_at',
                'priority',
                'risk_level',
                'project_manager_id',
                'assigned_team',
                'tags',
                'planned_delivery_date',
                'actual_delivery_date',
                'warranty_end_date',
                'staging_url',
                'production_url',
                'repository_url',
                'server_info',
                'maintenance_type',
                'quality_score',
                'client_satisfaction',
            ]);

            // Re-add old columns
            $table->unsignedTinyInteger('type_of_client')->default(1);
            $table->unsignedInteger('pricing')->default(0);
        });
    }
};
