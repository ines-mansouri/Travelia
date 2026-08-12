<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('passenger_id')
                ->constrained('passengers')
                ->cascadeOnDelete();

            $table->enum('city', ['makkah', 'madinah']);

            $table->string('room_number', 20)->nullable();
            $table->string('hotel_name', 255)->nullable();

            $table->enum('room_type', ['double', 'triple', 'quad', 'quint'])
                ->default('quad');

            $table->string('card_number', 50)->nullable()
                ->comment('Hotel room card / key number');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['passenger_id', 'city'], 'room_assignments_passenger_city_unique');
            $table->index(['booking_id', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
