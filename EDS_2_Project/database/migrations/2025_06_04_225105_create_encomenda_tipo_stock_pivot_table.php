<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// In the new migration file
public function up()
{
    Schema::create('encomenda_tipo_stock', function (Blueprint $table) {
        $table->id();
        $table->foreignId('encomenda_id')->constrained()->cascadeOnDelete();
        $table->foreignId('tipo_stock_id')->constrained()->cascadeOnDelete();
        $table->integer('stock_quantity');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('encomenda_tipo_stock');
}
};
