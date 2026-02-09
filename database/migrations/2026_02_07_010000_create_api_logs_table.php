<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method', 10);
            $table->string('url', 2048);
            $table->string('route')->nullable();
            $table->json('headers');
            $table->json('input')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->integer('status_code');
            $table->json('response_body')->nullable();
            $table->integer('duration_ms');
            $table->timestamps();

            $table->index('user_id');
            $table->index('created_at');
            $table->index('route');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
