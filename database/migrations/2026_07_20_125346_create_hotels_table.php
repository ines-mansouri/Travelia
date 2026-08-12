<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('country')->default('TN');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedTinyInteger('stars')->default(3);
            $table->decimal('price_per_night_usd', 10, 2);
            $table->json('images')->nullable();
            $table->json('amenities')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['city', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
