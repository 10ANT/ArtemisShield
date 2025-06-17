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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('alert_id')->constrained('alerts')->onDelete('cascade');
            $table->enum('status', ['OK', 'Needs Assistance', 'Unresponsive']);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure a user can only check in once per incident
            $table->unique(['user_id', 'alert_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
