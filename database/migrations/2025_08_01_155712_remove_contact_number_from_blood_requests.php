<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropColumn('contact_number');
        });
    }

    public function down()
    {
        Schema::table('blood_requests', function (Blueprint $table) {
            $table->string('contact_number')->nullable()->after('notes');
        });
    }
};
