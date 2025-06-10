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
        Schema::create('fire_hydrants', function (Blueprint $table) {
            $table->unsignedBigInteger('osm_id')->primary(); // Use OSM ID as primary key
            $table->string('type')->nullable(); // node, way, etc.
            $table->decimal('lat', 10, 7); // Latitude
            $table->decimal('lon', 10, 7); // Longitude
            $table->string('emergency')->nullable(); // e.g., fire_hydrant
            $table->string('fire_hydrant_type')->nullable(); // e.g., pillar
            $table->string('fire_hydrant_diameter')->nullable();
            $table->string('operator')->nullable();
            $table->string('colour')->nullable();
            $table->string('color')->nullable(); // Note: both 'colour' and 'color' exist
            $table->string('ref')->nullable();
            $table->text('description')->nullable();
            $table->string('addr_street')->nullable();
            $table->string('addr_housenumber')->nullable();
            $table->string('addr_city')->nullable();
            $table->string('addr_postcode')->nullable();
            $table->string('addr_state')->nullable();
            $table->string('source')->nullable();
            $table->string('survey_date')->nullable();
            $table->string('fire_hydrant_position')->nullable();
            $table->string('fire_hydrant_pressure')->nullable();
            $table->string('access')->nullable();
            $table->text('note')->nullable();
            $table->string('water_source')->nullable();
            $table->dateTime('osm_timestamp')->nullable(); // Renamed to avoid conflict with Laravel's
            $table->unsignedInteger('osm_version')->nullable(); // Renamed
            $table->unsignedBigInteger('osm_changeset')->nullable(); // Renamed
            $table->string('osm_user')->nullable(); // Renamed
            $table->unsignedBigInteger('osm_uid')->nullable(); // Renamed
            $table->json('all_tags')->nullable(); // To store the full tags JSON

            // Add indexes for frequently queried columns if needed, e.g., spatial index for lat/lon
            $table->index(['lat', 'lon']);
            // If you plan to query by city/state, add indexes:
            $table->index('addr_city');
            $table->index('addr_state');

            $table->timestamps(); // For Laravel's created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_hydrants');
    }
};