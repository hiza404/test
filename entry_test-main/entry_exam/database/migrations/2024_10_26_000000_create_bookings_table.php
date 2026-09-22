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
        Schema::create('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->autoIncrement();
            $table->unsignedBigInteger('hotel_id')->comment('foreign key to hotels table');
            $table->string('customer_name', 255)->comment('customer name');
            $table->string('customer_contact', 255)->comment('customer contact information');
            $table->timestamp('chekin_time')->nullable()->comment('check-in time');
            $table->timestamp('checkout_time')->nullable()->comment('check-out time');
            $table->timestamps();

            $table->foreign('hotel_id')->references('hotel_id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

