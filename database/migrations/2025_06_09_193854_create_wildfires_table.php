<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWildfiresTable extends Migration
{
    public function up()
    {
        Schema::create('wildfires', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('severity');
            $table->string('status');
            $table->timestamp('started_at');
            $table->timestamp('contained_at')->nullable();
            $table->json('predicted_path')->nullable();
            $table->decimal('affected_area', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wildfires');
    }
}