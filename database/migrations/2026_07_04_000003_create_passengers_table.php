<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();

            // Master booking link
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();

            // Personal info
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality', 100)->nullable();

            // CIN (National ID) — local compliance
            $table->string('cin', 50)->nullable();
            $table->date('cin_expiry_date')->nullable();

            // Passport — international travel
            $table->string('passport_number', 50)->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();

            // Contact
            $table->string('phone', 30)->nullable();
            $table->string('email', 255)->nullable();

            // Group / family role within this booking
            $table->string('relationship', 50)->nullable()
                ->comment('self, spouse, child, parent, sibling, other');

            // Hajj/Omrah specific: Mahram tracking for female passengers
            $table->foreignId('mahram_id')->nullable()->constrained('passengers')->nullOnDelete();
            $table->string('mahram_relationship', 50)->nullable();

            // Minor tracking (child pricing eligibility)
            $table->boolean('is_minor')->default(false);

            // Emergency contact
            $table->string('emergency_contact_name', 255)->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();

            // Visa status per passenger
            $table->string('visa_status', 30)->default('not_applied')
                ->comment('not_applied, pending, approved, rejected, received');

            // Medical & special requirements
            $table->text('medical_conditions')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes for search
            $table->index('first_name');
            $table->index('last_name');
            $table->index('cin');
            $table->index('passport_number');
            $table->index('visa_status');
            $table->index(['booking_id', 'first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
