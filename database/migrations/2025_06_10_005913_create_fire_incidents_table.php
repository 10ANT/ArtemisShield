<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fire_incidents', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('brightness', 8, 2);
            $table->decimal('scan', 8, 2)->nullable();
            $table->decimal('track', 8, 2)->nullable();
            $table->date('acq_date');
            $table->time('acq_time');
            $table->string('satellite', 50);
            $table->string('instrument', 50);
            $table->integer('confidence');
            $table->string('version', 20);
            $table->decimal('bright_t31', 8, 2)->nullable();
            $table->decimal('frp', 8, 2)->nullable();
            $table->char('daynight', 1);
            $table->integer('type');
            $table->string('source', 50); // MODIS_SP, VIIRS_SNPP_NRT, etc.
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
            $table->index(['acq_date', 'acq_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fire_incidents');
    }
};