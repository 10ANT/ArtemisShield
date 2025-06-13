<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('fire_incidents', function (Blueprint $table) {
        // This index ensures that the combination of these four columns is unique,
        // which makes the upsert operation much faster and prevents duplicates.
        $table->unique(['latitude', 'longitude', 'acq_date', 'satellite'], 'fire_incidents_unique_detection_index');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fire_incidents', function (Blueprint $table) {
            //
        });
    }
};
