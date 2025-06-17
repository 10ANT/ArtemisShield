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
        Schema::table('alerts', function (Blueprint $table) {
            // This changes the 'radius' column to a FLOAT type,
            // which can handle large numbers with decimals.
            $table->float('radius')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            // This allows you to reverse the change if needed.
            // We'll assume it was an integer before.
            $table->integer('radius')->change();
        });
    }
};