<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable'); // vehicle_requests or travel_requests
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade'); // the approver
            $table->enum('role', ['Motor Pool', 'Dean', 'Vice President', 'SUC President']);
            $table->enum('status', [
                'Pending',
                'Approved',
                'Rejected',
                'Vehicle Available',
                'No Vehicle Available',
                'Cancelled by User'
            ])->default('Pending');
            $table->text('comment')->nullable();
            $table->string('signature')->nullable(); // digital signature filename
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};