<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove unnecessary columns
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn(['urgency_level']);
        });

        // Add missing columns
        Schema::table('recipients', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->text('medical_notes')->nullable()->change();
            $table->text('special_requirements')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            $table->enum('urgency_level', ['low', 'normal', 'high'])->default('normal')->nullable(false)->change();
        });

        Schema::table('recipients', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->string('medical_notes')->nullable(false)->change();
            $table->string('special_requirements')->nullable(false)->change();
        });
    }
};
