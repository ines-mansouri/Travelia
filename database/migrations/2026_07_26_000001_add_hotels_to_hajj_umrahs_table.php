<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hajj_umrahs', function (Blueprint $table) {
            $table->foreignId('mecca_hotel_id')->nullable()->after('duration_days')->constrained('hotels')->nullOnDelete();
            $table->foreignId('medina_hotel_id')->nullable()->after('mecca_hotel_id')->constrained('hotels')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hajj_umrahs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mecca_hotel_id');
            $table->dropConstrainedForeignId('medina_hotel_id');
        });
    }
};
