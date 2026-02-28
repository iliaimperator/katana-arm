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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('car_id')->constrained('cars', 'car_id');
            $table->string('order_number')->unique(); // Номер заказ-наряда
            $table->date('order_date'); // Дата создания
            $table->date('reception_date'); // Дата приема
            $table->date('planned_completion_date')->nullable(); // Плановая дата завершения
            $table->date('actual_completion_date')->nullable(); // Фактическая дата завершения
            $table->decimal('total_cost', 12, 2)->default(0); // Общая стоимость
            $table->decimal('prepayment_amount', 12, 2)->default(0); // Сумма предоплаты
            $table->decimal('final_cost', 12, 2)->default(0); // Итоговая стоимость
            $table->enum('status', [
                'draft', // Черновик
                'accepted', // Принят
                'in_progress', // В работе
                'waiting_parts', // Ожидает запчасти
                'ready', // Готов
                'completed', // Завершен
                'cancelled' // Отменен
            ])->default('draft');
            $table->text('problem_description')->nullable(); // Описание проблемы
            $table->text('work_description')->nullable(); // Описание работ
            $table->text('recommendations')->nullable(); // Рекомендации
            $table->integer('mileage')->nullable(); // Пробег
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
