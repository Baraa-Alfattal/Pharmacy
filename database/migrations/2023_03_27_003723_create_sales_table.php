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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            //$table->unsignedInteger("pharmacy_id");
            $table->bigInteger('medicne_id')->unsigned()->nullable();
            $table->foreign('medicne_id')->references('id')->on('medicans')->cascadeOnDelete();
            $table->integer('quantity'); // الكمية المشتريات
            $table->decimal('total', 8, 2);
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
        Schema::dropIfExists('sales');
    }
};
