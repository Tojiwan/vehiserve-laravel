<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['request_id']);
            // Drop the old column
            $table->dropColumn('request_id');
            // Add new column referencing trip_requests
            $table->foreignId('request_id')->constrained('trip_requests')->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to handle the FK drop more reliably
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::table('passengers', function (Blueprint $table) {
            // Drop any existing FK on request_id
            $table->dropForeign(['request_id']);
            // Drop the column
            $table->dropColumn('request_id');
            // Re-add the column (without FK constraint since vehicle_requests may not exist)
            $table->foreignId('request_id')->nullable()->after('id');
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};