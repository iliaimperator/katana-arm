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
        Schema::create('work_order_parts', function (Blueprint $table) {
            $table->id('order_part_id');
            $table->foreignId('order_id')->constrained('work_orders', 'order_id')->onDelete('cascade');
            $table->foreignId('part_id')->constrained('spare_parts', 'part_id');
            $table->integer('quantity')->default(1); // Количество
            $table->decimal('unit_price', 10, 2); // Цена за единицу
            $table->decimal('total_price', 10, 2); // Общая цена
            $table->enum('status', ['ordered', 'in_stock', 'used'])->default('ordered'); // Статус запчасти
            $table->text('notes')->nullable(); // Примечания
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_parts');
    }
};
