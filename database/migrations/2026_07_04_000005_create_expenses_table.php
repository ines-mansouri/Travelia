<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('TND');

            $table->string('reference_number', 100)->nullable()
                ->comment('Supplier invoice or bill reference');

            $table->enum('category', [
                'flight_ticket',
                'hotel_booking',
                'visa_fees',
                'transport',
                'local_agent_fees',
                'insurance',
                'marketing',
                'rent',
                'utilities',
                'salaries',
                'supplies',
                'other',
            ])->default('other');

            $table->enum('payment_status', ['paid', 'partial', 'unpaid'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();

            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('category');
            $table->index('payment_status');
            $table->index('due_date');
            $table->index(['destination_id', 'booking_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
