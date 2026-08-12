<?php

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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('stripe_session_id', 255)->nullable()->unique()->after('payment_notes');
            $table->string('stripe_payment_intent_id', 255)->nullable()->after('stripe_session_id');
            $table->decimal('original_price_usd', 10, 2)->nullable()->after('stripe_payment_intent_id');
            $table->decimal('converted_price', 10, 2)->nullable()->after('original_price_usd');
            $table->string('currency_code', 3)->nullable()->after('converted_price');
            $table->string('customer_email')->nullable()->after('currency_code');
            $table->string('customer_name')->nullable()->after('customer_email');

            // Extend status enum to include paid / refunding
            $table->string('status', 20)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_session_id',
                'stripe_payment_intent_id',
                'original_price_usd',
                'converted_price',
                'currency_code',
                'customer_email',
                'customer_name',
            ]);

            // Restore original enum (some DBs may need manual revert)
            $table->string('status', 20)->default('pending')->change();
        });
    }
};
