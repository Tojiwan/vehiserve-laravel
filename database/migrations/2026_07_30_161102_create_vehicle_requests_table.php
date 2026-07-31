<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade');
            $table->date('request_date');
            $table->string('requesting_person');
            $table->string('office_college');
            $table->string('destination');
            $table->text('purpose');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->string('signature')->nullable(); // signature image filename
            $table->string('valid_id')->nullable(); // valid_id image filename
            $table->integer('num_passengers')->default(1);
            $table->string('vehicle_status')->default('Pending Motor Pool'); // Vehicle availability status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_requests');
    }
};