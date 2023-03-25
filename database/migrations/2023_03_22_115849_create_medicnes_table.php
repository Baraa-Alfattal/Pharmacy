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
        Schema::create('medicnes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pharmacy_id')->unsigned()->nullable();
            $table->foreign('pharmacy_id')->references('id')->on('pharmacies')->cascadeOnDelete();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('uses_for');
            $table->string('effects');
            $table->integer('quantity');
            $table->dateTime('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicnes');
    }
};
