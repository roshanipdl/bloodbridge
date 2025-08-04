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
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'donation_history',
                'health_notes',
                'next_eligible_donation_date',
                'medical_conditions',
                'last_health_check_date',
                'donations_in_last_2_years'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->longText('donation_history')->nullable()->charset('utf8mb4')->collation('utf8mb4_bin');
            $table->text('health_notes')->nullable();
            $table->date('next_eligible_donation_date')->nullable();
            $table->longText('medical_conditions')->nullable()->charset('utf8mb4')->collation('utf8mb4_bin');
            $table->date('last_health_check_date')->nullable();
            $table->integer('donations_in_last_2_years')->default(0);
        });
    }
};
