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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('number');
            $table->dateTime('b_day');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('img')->nullable();
            $table->string('medicine_used',100)->nullable();
            $table->string('medicine_allergies')->nullable();
            $table->string('food_allergies')->nullable();
            $table->string('have_disease')->nullable();
            $table->string('another_disease')->nullable();

            //Add notifications column in database.
            $table->json('notifications')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
