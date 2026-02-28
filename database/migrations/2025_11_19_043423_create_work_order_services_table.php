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
        Schema::create('work_order_services', function (Blueprint $table) {
            $table->id('order_service_id');
            $table->foreignId('order_id')->constrained('work_orders', 'order_id')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services', 'service_id');
            $table->integer('quantity')->default(1); // Количество
            $table->decimal('unit_price', 10, 2); // Цена за единицу
            $table->decimal('total_price', 10, 2); // Общая цена
            $table->text('notes')->nullable(); // Примечания
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_services');
    }
};
