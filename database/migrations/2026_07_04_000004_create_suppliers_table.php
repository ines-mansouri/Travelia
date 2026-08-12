<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->enum('type', [
                'hotel',
                'airline',
                'visa_provider',
                'transport',
                'local_agent',
                'insurance',
                'other',
            ])->default('other');

            $table->string('contact_name', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();

            $table->decimal('balance', 12, 2)->default(0.00)
                ->comment('Total outstanding amount owed to this supplier');

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
