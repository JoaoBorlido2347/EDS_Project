<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('localizacoes')) {
            Schema::create('localizacoes', function (Blueprint $table) {
                $table->id();
                $table->string('piso');
                $table->string('corredor');
                $table->string('prateleira');
                $table->boolean('is_empty')->default(true);
                $table->timestamps();
                
                $table->unique(['piso', 'corredor', 'prateleira']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('localizacoes');
    }
};