<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reviewable');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'reviewable_id', 'reviewable_type'], 'booking_reviews_unique');
            $table->index(['reviewable_id', 'reviewable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_reviews');
    }
};
