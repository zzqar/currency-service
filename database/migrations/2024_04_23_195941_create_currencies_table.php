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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id(); // Автоматически увеличивающийся первичный ключ (ID)
            $table->string('currency_id'); // Идентификатор валюты
            $table->date('date'); // Дата
            $table->string('num_code'); // Числовой код валюты
            $table->string('char_code'); // Буквенный код валюты
            $table->string('nominal'); // Номинал
            $table->string('name'); // Наименование валюты
            $table->string('value'); // Значение курса валюты
            $table->string('vunit_rate'); // Курс валюты
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
