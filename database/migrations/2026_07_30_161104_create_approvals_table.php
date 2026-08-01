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
            $table->morphs('approvable');
            $table->foreignId('user_ID')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role');
            $table->string('status');
            $table->text('comment')->nullable();
            $table->string('signature')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};