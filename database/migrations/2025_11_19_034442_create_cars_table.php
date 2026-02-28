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
        Schema::create('cars', function (Blueprint $table) {
            $table->id('car_id');
            $table->foreignId('client_id')->constrained('clients', 'client_id')->onDelete('cascade');
            $table->string('vin')->unique()->nullable(); // VIN номер
            $table->string('license_plate')->unique(); // Госномер
            $table->string('brand'); // Марка
            $table->string('model'); // Модель
            $table->integer('year'); // Год выпуска
            $table->string('engine_model')->nullable(); // Модель двигателя
            $table->decimal('engine_volume', 3, 1)->nullable(); // Объем двигателя
            $table->string('transmission')->nullable(); // КПП (manual/auto)
            $table->string('color')->nullable(); // Цвет
            $table->text('notes')->nullable(); // Заметки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
