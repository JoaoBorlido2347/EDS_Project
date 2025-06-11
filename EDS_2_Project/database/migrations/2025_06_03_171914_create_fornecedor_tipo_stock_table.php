<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fornecedor_tipo_stock', function (Blueprint $table) {
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->foreignId('tipo_stock_id')->constrained('tipo_stocks')->cascadeOnDelete(); // Fixed table name
            $table->primary(['fornecedor_id', 'tipo_stock_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor_tipo_stock');
    }
};