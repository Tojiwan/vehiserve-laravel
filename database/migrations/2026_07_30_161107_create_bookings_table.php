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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_ID');
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade');
            $table->string('requesting_personnel');
            $table->foreignId('driver_ID')->constrained('drivers', 'driver_ID')->onDelete('cascade');
            $table->foreignId('vehicle_ID')->constrained('vehicles', 'vehicle_ID')->onDelete('cascade');
            $table->integer('num_passengers')->default(1);
            $table->string('destination');
            $table->enum('status', ['Booked', 'Completed', 'Cancelled'])->default('Booked');
            $table->date('date');
            $table->date('return_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
