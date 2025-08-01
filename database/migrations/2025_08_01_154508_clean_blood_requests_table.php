<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
        });

        // Drop unnecessary columns
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn(['hospital_name', 'hospital_address', 'contact_number', 'request_date', 'fulfill_date', 'donor_id']);
        });
    }

    public function down(): void
    {
        // Add columns first
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->string('hospital_name');
            $table->string('hospital_address');
            $table->string('contact_number');
            $table->date('request_date');
            $table->date('fulfill_date')->nullable();
            $table->foreignId('donor_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Add foreign key constraint
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->foreign('donor_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
