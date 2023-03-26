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
        Schema::create('medicans', function (Blueprint $table) {
            
            $table->id(); // الحقل الرئيسي (Primary key)
            $table->unsignedInteger("pharmacy_id");
            $table->string('name'); // اسم الدواء
            $table->decimal('price',8,2); 
            $table->text('description')->nullable(); // وصف الدواء
            $table->integer('quantity')->nullable(); // الكمية المتاحة
            $table->timestamps(); // تاريخ إنشاء وتحديث الصف

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicans');
    }
};
