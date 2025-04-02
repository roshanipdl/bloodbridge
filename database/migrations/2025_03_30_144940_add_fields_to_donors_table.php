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
            // Only add fields that don't exist
            if (!Schema::hasColumn('donors', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('id');
            }
            if (!Schema::hasColumn('donors', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('donors', 'last_donation_date')) {
                $table->date('last_donation_date')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('donors', 'blood_type')) {
                $table->string('blood_type', 3)->after('last_donation_date');
            }
            if (!Schema::hasColumn('donors', 'total_donations')) {
                $table->integer('total_donations')->default(0)->after('blood_type');
            }
            if (!Schema::hasColumn('donors', 'is_available')) {
                $table->boolean('is_available')->default(1)->after('total_donations');
            }
            if (!Schema::hasColumn('donors', 'health_status')) {
                $table->boolean('health_status')->default(1)->after('is_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'last_donation_date', 'blood_type', 'total_donations', 'is_available', 'health_status']);
        });
    }
};
