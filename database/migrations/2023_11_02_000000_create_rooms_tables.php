<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_type', function (Blueprint $table) {
            $table->id('RoomTypeID');
            $table->string('TypeName');
            $table->text('Description')->nullable();
            $table->integer('Capacity')->default(2);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id('RoomID');
            $table->string('RoomNumber', 10);
            $table->unsignedBigInteger('RoomTypeID');
            $table->string('Status', 20)->default('Available');
            $table->integer('Single_Bed')->default(1);
            $table->integer('Double_Bed')->default(0);
            $table->timestamps();

            $table->foreign('RoomTypeID')
                  ->references('RoomTypeID')
                  ->on('room_type')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_type');
    }
};