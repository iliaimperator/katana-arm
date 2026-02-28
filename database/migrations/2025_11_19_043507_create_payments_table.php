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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('work_orders', 'order_id')->onDelete('cascade');
            $table->decimal('amount', 12, 2); // Сумма платежа
            $table->enum('type', ['prepayment', 'final']); // Тип платежа
            $table->enum('method', ['cash', 'card', 'transfer']); // Способ оплаты
            $table->date('payment_date'); // Дата платежа
            $table->text('notes')->nullable(); // Примечания
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
