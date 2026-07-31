<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade');
            $table->string('personnel_name');
            $table->string('official_station');
            $table->string('destination');
            $table->text('purpose');
            $table->date('inclusive_date');
            $table->enum('requesting_for', ['Cash Advance', 'Reimbursement', 'N/A'])->default('N/A');
            $table->enum('vehicle_request', ['Yes', 'No', 'N/A'])->default('N/A');
            $table->string('vehicle_status')->default('Pending'); // Vehicle availability status
            $table->string('signature')->nullable(); // signature image filename
            $table->string('valid_id')->nullable(); // valid_id image filename
            $table->string('dean_signature')->nullable(); // dean signature filename
            $table->string('vp_signature')->nullable(); // vp signature filename
            $table->string('suc_signature')->nullable(); // suc signature filename
            $table->text('comment')->nullable(); // rejection comment
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_requests');
    }
};