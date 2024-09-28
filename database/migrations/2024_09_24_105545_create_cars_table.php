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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->string('status')->default('not_sold');
            $table->decimal('start_price')->default('0.00');
            $table->decimal('current_price')->default('0.00');
            $table->foreignId('current_winner_id')->nullable()
                ->constrained('customers')->nullOnDelete('cascade');


            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
