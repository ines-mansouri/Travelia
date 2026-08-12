<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('offer_id', 50)->nullable();
            $table->string('origin', 3);
            $table->string('destination', 3);
            $table->date('depart_date');
            $table->date('return_date')->nullable();
            $table->string('partner', 30);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('clicked_at')->useCurrent();

            // Index for analytics queries
            $table->index(['partner', 'clicked_at']);
            $table->index(['origin', 'destination']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
    }
};
