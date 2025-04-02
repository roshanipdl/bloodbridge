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
            // Drop the old blood_group column if it exists
            if (Schema::hasColumn('recipients', 'blood_group')) {
                $table->dropColumn('blood_group');
            }
            
            // Add blood_type_needed if it doesn't exist
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
        Schema::table('recipients', function (Blueprint $table) {
            if (Schema::hasColumn('recipients', 'blood_type_needed')) {
                $table->dropColumn('blood_type_needed');
            }
            $table->string('blood_group');
        });
    }
};
