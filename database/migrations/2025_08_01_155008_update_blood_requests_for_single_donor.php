<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the pivot table first
        Schema::dropIfExists('blood_request_donor');

        // Add back donor_id column with foreign key
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->foreignId('donor_id')->nullable()->constrained('donors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Drop foreign key first
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
        });

        // Create pivot table
        Schema::create('blood_request_donor', function (Blueprint $table) {
            $table->foreignId('blood_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }
};
