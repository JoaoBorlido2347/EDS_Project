<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->enum('estado', ['Pendente', 'Recebida','Cancelada'])->default('Pendente');
            $table->enum('estado', ['Pendente', 'Recebida','Cancelada'])->default('Pendente');
            $table->foreignId('gestor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->timestamps();
        });

    }

    public function down(): void
    {

        Schema::dropIfExists('encomendas');
    }
};