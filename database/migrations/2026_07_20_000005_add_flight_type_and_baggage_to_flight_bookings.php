<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->string('flight_type', 20)->default('one_way')->after('currency_symbol');
            $table->json('legs')->nullable()->after('flight_details');
            $table->unsignedTinyInteger('cabin_bags')->default(1)->after('legs');
            $table->unsignedTinyInteger('checked_bags')->default(0)->after('cabin_bags');
            $table->decimal('baggage_original_price', 10, 2)->default(0)->after('checked_bags');
            $table->decimal('baggage_converted_price', 10, 2)->default(0)->after('baggage_original_price');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'flight_type',
                'legs',
                'cabin_bags',
                'checked_bags',
                'baggage_original_price',
                'baggage_converted_price',
            ]);
        });
    }
};
