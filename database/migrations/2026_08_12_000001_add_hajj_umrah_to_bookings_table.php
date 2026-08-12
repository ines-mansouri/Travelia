<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('hajj_umrah_id')
                ->nullable()
                ->after('destination_id')
                ->constrained('hajj_umrahs')
                ->nullOnDelete();

            $table->foreignId('destination_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['hajj_umrah_id']);
            $table->dropColumn('hajj_umrah_id');

            $table->foreignId('destination_id')->nullable(false)->change();
        });
    }
};