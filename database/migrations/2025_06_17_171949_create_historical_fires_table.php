<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is executed when you run the `php artisan migrate` command.
     * It creates the structure for our historical_fires table.
     */
    public function up(): void
    {
        Schema::create('historical_fires', function (Blueprint $table) {
            // Creates an auto-incrementing BIGINT primary key column named 'id'
            $table->id();

            // Geographic coordinates. Decimal is used for high precision.
            // 10 total digits, 6 after the decimal point is good for lat/lng.
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);

            // Fire attributes. Most are nullable as data might be missing in some rows.
            $table->float('brightness')->nullable();
            $table->float('scan')->nullable();
            $table->float('track')->nullable();
            $table->date('acq_date')->nullable();
            $table->string('acq_time', 4)->nullable(); // e.g., '0333'
            $table->string('satellite', 20)->nullable();
            $table->string('instrument', 20)->nullable();
            $table->integer('confidence')->nullable();
            $table->string('version', 10)->nullable();
            $table->float('bright_t31')->nullable();
            $table->float('frp')->nullable(); // Fire Radiative Power
            $table->string('daynight', 5)->nullable();
            $table->integer('type')->nullable();

            // We are not using Laravel's default created_at/updated_at timestamps
            // because the data is historical and static.
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method is executed when you run `php artisan migrate:rollback`.
     * It safely drops the table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('historical_fires');
    }
};