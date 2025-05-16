<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (!Schema::hasColumn('donors', 'health_status')) {
                $table->enum('health_status', ['good', 'pending_review', 'not_eligible'])->default('pending_review');
            }
            if (!Schema::hasColumn('donors', 'last_health_check_date')) {
                $table->date('last_health_check_date')->nullable();
            }
            if (!Schema::hasColumn('donors', 'donations_in_last_2_years')) {
                $table->integer('donations_in_last_2_years')->default(0);
            }
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('blood_requests', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('blood_requests', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('blood_requests', 'required_by_date')) {
                $table->date('required_by_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'health_status',
                'last_health_check_date',
                'donations_in_last_2_years'
            ]);
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'required_by_date'
            ]);
        });
    }
};