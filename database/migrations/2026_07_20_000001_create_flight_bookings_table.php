<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('stripe_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->json('flight_details');
            $table->decimal('original_price_usd', 10, 2);
            $table->decimal('converted_price', 10, 2);
            $table->string('currency_code', 3)->default('TND');
            $table->string('currency_symbol', 5)->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->timestamps();

            $table->index('stripe_session_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
