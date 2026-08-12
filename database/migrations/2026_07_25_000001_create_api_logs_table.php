<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50);
            $table->string('endpoint', 255);
            $table->string('method', 10)->default('GET');
            $table->string('correlation_id', 36)->nullable()->index();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->boolean('success')->default(false);
            $table->string('error_code', 50)->nullable();
            $table->text('error_message')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_meta')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['provider', 'created_at']);
            $table->index(['success', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
