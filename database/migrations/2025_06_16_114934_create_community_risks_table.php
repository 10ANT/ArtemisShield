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
        Schema::create('community_risks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('state_abbreviation', 4);
            $table->string('county_name');
            $table->integer('population')->nullable();
            $table->string('risk_to_homes_text')->nullable();
            $table->string('whp_text')->nullable();
            $table->decimal('exposure', 8, 7)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();

            $table->index('state_abbreviation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_risks');
    }
};