<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id('Feedback_ID');
            $table->foreignId('user_ID')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('satisfaction', ['Yes', 'No']);
            $table->text('comments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};