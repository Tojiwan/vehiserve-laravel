<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_ID');
            $table->string('vehicle_name');
            $table->string('plate_number')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->integer('capacity')->nullable();
            $table->enum('status', ['Available', 'On Trip', 'Maintenance', 'Inactive'])->default('Available');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->date('date_acquired')->nullable();
            $table->date('date_last_maintained')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};