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
        Schema::table('status_updates', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('contact_number');
            $table->decimal('longitude', 11, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_updates', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};