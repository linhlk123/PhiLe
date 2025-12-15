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
        Schema::create('PAYMENTS', function (Blueprint $table) {
            $table->id('PaymentID');
            $table->unsignedBigInteger('BookingID');
            $table->dateTime('PaymentDate')->default(now());
            $table->decimal('Amount', 12, 2);
            $table->string('PaymentMethod'); // cash, transfer, credit_card
            $table->string('PaymentStatus')->default('paid'); // paid, pending, cancelled
            
            $table->foreign('BookingID')->references('BookingID')->on('BOOKINGS')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PAYMENTS');
    }
};
