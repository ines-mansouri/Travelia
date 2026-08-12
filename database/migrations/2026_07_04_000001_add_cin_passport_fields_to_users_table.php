<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cin', 50)->nullable()->after('email');
            $table->string('passport_number', 50)->nullable()->after('cin');
            $table->date('passport_issue_date')->nullable()->after('passport_number');
            $table->date('passport_expiry_date')->nullable()->after('passport_issue_date');
            $table->date('birth_date')->nullable()->after('passport_expiry_date');
            $table->string('phone', 30)->nullable()->after('birth_date');
            $table->string('emergency_contact', 100)->nullable()->after('phone');
            $table->text('address')->nullable()->after('emergency_contact');
            $table->enum('gender', ['male', 'female'])->nullable()->after('address');
            $table->string('nationality', 100)->nullable()->after('gender');
            $table->boolean('is_active')->default(true)->after('nationality');

            $table->index('cin');
            $table->index('passport_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cin',
                'passport_number',
                'passport_issue_date',
                'passport_expiry_date',
                'birth_date',
                'phone',
                'emergency_contact',
                'address',
                'gender',
                'nationality',
                'is_active',
            ]);
        });
    }
};
