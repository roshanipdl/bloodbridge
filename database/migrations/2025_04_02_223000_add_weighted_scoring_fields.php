<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add remaining fields to donors table for weighted scoring
        Schema::table('donors', function (Blueprint $table) {
            if (!Schema::hasColumn('donors', 'donation_history')) {
                $table->json('donation_history')->nullable(); // Store dates of past donations
            }
            if (!Schema::hasColumn('donors', 'health_notes')) {
                $table->text('health_notes')->nullable();
            }
            if (!Schema::hasColumn('donors', 'next_eligible_donation_date')) {
                $table->date('next_eligible_donation_date')->nullable();
            }
            if (!Schema::hasColumn('donors', 'medical_conditions')) {
                $table->json('medical_conditions')->nullable();
            }
        });

        // Add weighted scoring fields to recipients table
        Schema::table('recipients', function (Blueprint $table) {
            if (!Schema::hasColumn('recipients', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('recipients', 'longitude')) {
                $table->decimal('longitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('recipients', 'request_timestamp')) {
                $table->timestamp('request_timestamp')->useCurrent();
            }
            if (!Schema::hasColumn('recipients', 'urgency_level')) {
                $table->string('urgency_level')->default('normal'); // Can be: normal, urgent, emergency
            }
            if (!Schema::hasColumn('recipients', 'medical_notes')) {
                $table->text('medical_notes')->nullable();
            }
            if (!Schema::hasColumn('recipients', 'special_requirements')) {
                $table->json('special_requirements')->nullable();
            }
            if (!Schema::hasColumn('recipients', 'blood_type_needed')) {
                $table->string('blood_type_needed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'donation_history',
                'health_notes',
                'next_eligible_donation_date',
                'medical_conditions'
            ]);
        });

        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'blood_type_needed',
                'request_timestamp',
                'urgency_level',
                'medical_notes',
                'special_requirements'
            ]);
        });
    }
};
