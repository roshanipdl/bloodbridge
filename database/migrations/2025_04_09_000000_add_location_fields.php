<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->string('place_name')->nullable()->after('hospital_address');
            $table->string('city')->nullable()->after('place_name');
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->string('place_name')->nullable()->after('longitude');
            $table->string('city')->nullable()->after('place_name');
        });
    }

    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn(['place_name', 'city']);
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['place_name', 'city']);
        });
    }
}; 