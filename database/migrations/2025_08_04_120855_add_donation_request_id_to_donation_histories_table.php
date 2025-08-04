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
        Schema::table('donation_histories', function (Blueprint $table) {
            $table->foreignId('donation_request_id')->constrained('donation_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_histories', function (Blueprint $table) {
            $table->dropForeign(['donation_request_id']);
            $table->dropColumn('donation_request_id');
        });
    }
};
