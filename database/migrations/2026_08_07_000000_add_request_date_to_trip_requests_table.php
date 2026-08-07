<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('trip_requests', 'request_date')) {
            Schema::table('trip_requests', function (Blueprint $table) {
                $table->date('request_date')->nullable()->after('user_ID');
            });

            DB::table('trip_requests')->update([
                'request_date' => DB::raw('DATE(created_at)'),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('trip_requests', 'request_date')) {
            Schema::table('trip_requests', function (Blueprint $table) {
                $table->dropColumn('request_date');
            });
        }
    }
};
