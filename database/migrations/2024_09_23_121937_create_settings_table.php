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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('insurance_rate', 5, 2);
            $table->string('time_zone')->default('Asia/Riyadh');
            $table->string('currency')->default('SAR');
            $table->string('default_language')->default('en');
            $table->decimal('minimum_bid_amount', 8, 2)->default(0.00);
            $table->decimal('max_bid_amount', 8, 2)->default(1.00);
            $table->text('refund_policy')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->decimal('service_fee_percentage', 5, 2)->default(5.00);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('website_title')->default('Auction Yard');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
