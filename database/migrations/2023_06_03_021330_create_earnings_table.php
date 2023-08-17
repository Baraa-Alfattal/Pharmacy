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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pharmacie_id')->unsigned()->nullable();
            $table->foreign('pharmacie_id')->references('id')->on('pharmacies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('date')->nullable();;
            $table->decimal('revenue', 8, 2);
            $table->decimal('cost', 8, 2);
            $table->decimal('earnings', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings');
    }
};
