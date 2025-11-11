<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('CustomerID');
            $table->string('FullName', 100);
            $table->string('Gender', 10);
            $table->string('Phone', 20);
            $table->string('Email', 100)->unique();
            $table->string('IDNumber', 20)->unique();
            $table->string('Address', 255);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};