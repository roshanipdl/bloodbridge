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
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn('blood_type_needed');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            $table->string('blood_type_needed')->after('medical_notes');
            $table->decimal('latitude', 10, 8)->after('blood_type_needed');
            $table->decimal('longitude', 11, 8)->after('latitude');
        });
    }
};
