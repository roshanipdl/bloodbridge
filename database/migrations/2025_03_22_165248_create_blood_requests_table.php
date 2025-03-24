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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_name');
            $table->string('blood_group');
            $table->integer('units_required');
            $table->string('hospital_name');
            $table->string('hospital_address');
            $table->string('contact_number');
            $table->enum('urgency_level', ['normal', 'urgent', 'critical'])->default('normal');
            $table->text('additional_info')->nullable();
            $table->enum('status', ['pending', 'fulfilled', 'cancelled'])->default('pending');
            $table->date('request_date');
            $table->date('fulfill_date')->nullable();
            $table->foreignId('donor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
