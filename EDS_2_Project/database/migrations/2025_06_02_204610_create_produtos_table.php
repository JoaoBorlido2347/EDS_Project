<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('tipo_stock_id')->constrained();
            $table->integer('quantidade')->default(0);
            $table->foreignId('localizacao_id')->nullable()->constrained('localizacoes')->onDelete('set null');
            $table->boolean('esgotado')->default(false)->after('quantidade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};