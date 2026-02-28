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
        Schema::create('clients', function (Blueprint $table) {
            $table->id('client_id');
            $table->string('last_name'); // Фамилия
            $table->string('first_name'); // Имя
            $table->string('middle_name')->nullable(); // Отчество
            $table->string('phone')->unique(); // Телефон
            $table->string('email')->nullable()->unique(); // Email
            $table->text('address')->nullable(); // Адрес
            $table->text('notes')->nullable(); // Заметки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
