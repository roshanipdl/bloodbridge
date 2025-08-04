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
        Schema::table('blood_requests', function (Blueprint $table) {
            // Drop the existing enum constraint
            DB::statement('ALTER TABLE blood_requests MODIFY status ENUM("pending", "fulfilled", "cancelled", "processing")');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            // Remove the processing status from enum
            DB::statement('ALTER TABLE blood_requests MODIFY status ENUM("pending", "fulfilled", "cancelled")');
        });
    }
};
