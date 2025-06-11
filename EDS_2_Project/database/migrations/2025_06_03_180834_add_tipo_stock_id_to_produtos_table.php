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
        if (!Schema::hasColumn('produtos', 'tipo_stock_id')) {
            Schema::table('produtos', function (Blueprint $table) {
                $table->foreignId('tipo_stock_id')->constrained('tipos_stock');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('produtos', 'tipo_stock_id')) {
            Schema::table('produtos', function (Blueprint $table) {
                $table->dropForeign(['tipo_stock_id']);
                $table->dropColumn('tipo_stock_id');
            });
        }
    }
};
