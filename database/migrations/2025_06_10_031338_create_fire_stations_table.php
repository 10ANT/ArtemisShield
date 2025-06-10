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
        Schema::create('fire_stations', function (Blueprint $table) {
            $table->id();
            $table->string('osm_id')->nullable(); // Using osm_id from your data
            $table->string('type')->nullable(); // node, way
            $table->decimal('lat', 10, 7); // Latitude
            $table->decimal('lon', 10, 7); // Longitude
            $table->string('amenity')->nullable(); // fire_station
            $table->string('name')->nullable();
            $table->string('official_name')->nullable();
            $table->string('alt_name')->nullable();
            $table->string('operator')->nullable();
            $table->string('operator_type')->nullable(); // operator:type
            $table->string('fire_station_type')->nullable(); // fire_station:type
            $table->string('addr_street')->nullable(); // addr:street
            $table->string('addr_housenumber')->nullable(); // addr:housenumber
            $table->string('addr_city')->nullable(); // addr:city
            $table->string('addr_postcode')->nullable(); // addr:postcode
            $table->string('addr_state')->nullable(); // addr:state
            $table->string('addr_country')->nullable(); // addr:country
            $table->string('phone')->nullable();
            $table->string('emergency')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('contact_phone')->nullable(); // contact:phone
            $table->string('contact_website')->nullable(); // contact:website
            $table->string('contact_email')->nullable(); // contact:email
            $table->string('source')->nullable();
            $table->string('building')->nullable();
            $table->string('building_levels')->nullable(); // building:levels
            $table->string('ref')->nullable();
            $table->string('ref_nfirs')->nullable(); // ref:nfirs
            $table->string('fire_station_code')->nullable(); // fire_station:code
            $table->text('description')->nullable();
            $table->string('wheelchair')->nullable();
            $table->string('access')->nullable();
            $table->text('note')->nullable();
            $table->string('wikidata')->nullable();
            $table->string('wikipedia')->nullable();
            $table->text('fire_station_apparatus')->nullable(); // fire_station:apparatus
            $table->text('fire_station_staffing')->nullable(); // fire_station:staffing

            // Store all_tags as JSON
            $table->json('all_tags')->nullable();

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_stations');
    }
};