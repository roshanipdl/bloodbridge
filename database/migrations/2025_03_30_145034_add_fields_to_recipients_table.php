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
            // Basic recipient information
            if (!Schema::hasColumn('recipients', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('recipients', 'contact')) {
                $table->string('contact');
            }
            if (!Schema::hasColumn('recipients', 'address')) {
                $table->string('address');
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
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn(['name', 'contact', 'address', 'blood_type_needed']);
        });
    }
};
