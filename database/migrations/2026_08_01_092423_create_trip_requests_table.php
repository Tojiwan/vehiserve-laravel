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
        Schema::create('trip_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade');
            
            // Travel fields
            $table->string('personnel_name');
            $table->string('official_station');
            $table->string('destination');
            $table->text('purpose');
            $table->date('inclusive_date');
            $table->enum('requesting_for', ['Cash Advance', 'Reimbursement', 'N/A'])->default('N/A');
            
            // Vehicle fields
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('return_date')->nullable();
            $table->integer('num_passengers')->default(1);
            $table->foreignId('vehicle_ID')->nullable()->constrained('vehicles', 'vehicle_ID')->nullOnDelete();
            $table->foreignId('driver_ID')->nullable()->constrained('drivers', 'driver_ID')->nullOnDelete();
            
            // Common status field
            $table->string('status')->default('Pending Dean');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_requests');
    }
};