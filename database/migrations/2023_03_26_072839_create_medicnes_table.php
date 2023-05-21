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
            $table->string('name');
            $table->string('scientific_name');
            $table->string('company_name');
            $table->string('category');
            $table->string('active_ingredient');
            $table->string('img')->nullable();
            $table->string('uses_for');
            $table->string('effects');
            // $table->integer('quantity');
            // $table->dateTime('expiry_date');
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
