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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('auction_start_time')->default('04:00')->after('insurance_rate');
            $table->string('auction_end_time')->default('21:00')->after('auction_start_time');
            $table->string('each_auction_minutes')->default('10')->after('auction_end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('auction_start_time');
            $table->dropColumn('auction_end_time');
            $table->dropColumn('each_auction_minutes');
        });
    }
};
