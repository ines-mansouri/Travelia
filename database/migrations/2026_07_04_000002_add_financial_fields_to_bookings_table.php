<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('amount_paid', 10, 2)->default(0.00)->after('total_price');
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('amount_paid');
            $table->date('deposit_due_date')->nullable()->after('deposit_amount');
            $table->date('balance_due_date')->nullable()->after('deposit_due_date');
            $table->string('payment_status', 30)->default('pending')->after('balance_due_date');
            $table->string('payment_method', 50)->nullable()->after('payment_status');
            $table->string('invoice_number', 50)->nullable()->after('payment_method');
            $table->string('voucher_code', 50)->nullable()->after('invoice_number');
            $table->text('payment_notes')->nullable()->after('voucher_code');

            $table->index('payment_status');
            $table->index('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'amount_paid',
                'deposit_amount',
                'deposit_due_date',
                'balance_due_date',
                'payment_status',
                'payment_method',
                'invoice_number',
                'voucher_code',
                'payment_notes',
            ]);
        });
    }
};
