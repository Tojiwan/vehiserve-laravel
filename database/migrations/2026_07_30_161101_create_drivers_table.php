<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id('driver_ID');
            $table->string('full_name');
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female']);
            $table->string('contact')->nullable();
            $table->date('date_joined')->nullable();
            $table->enum('status', ['Available', 'On Trip', 'On Leave', 'Off Duty'])->default('Available');
            $table->string('position')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};