<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('estado', ['Em_Progresso', 'Concluida'])->default('Em_Progresso');
            $table->enum('tipo', ['Receber', 'Mover', 'Enviar', 'Armazenar']);
            $table->foreignId('gestor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for tarefa_funcionario many-to-many relationship
        Schema::create('tarefa_funcionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade');
            $table->foreignId('funcionario_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for tarefa_produto many-to-many relationship
        Schema::create('tarefa_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarefa_id')->constrained('tarefas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefa_produto');
        Schema::dropIfExists('tarefa_funcionario');
        Schema::dropIfExists('tarefas');
    }
};