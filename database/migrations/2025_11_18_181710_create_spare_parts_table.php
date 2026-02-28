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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id('part_id');
            $table->string('article_number')->unique(); // Артикул
            $table->string('part_name'); // Наименование запчасти
            $table->text('description')->nullable(); // Описание
            $table->decimal('purchase_price', 10, 2); // Цена закупки
            $table->decimal('selling_price', 10, 2); // Цена продажи
            $table->integer('stock_quantity')->default(0); // Количество на складе
            $table->integer('min_stock')->default(0); // Минимальный запас
            $table->string('supplier')->nullable(); // Поставщик
            $table->string('category')->nullable(); // Категория (двигатель, КПП и т.д.)
            $table->boolean('is_active')->default(true); // Активна ли запчасть
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
