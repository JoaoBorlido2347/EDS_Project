<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only modify if table exists
        if (Schema::hasTable('encomenda_items')) {
            Schema::table('encomenda_items', function (Blueprint $table) {
                // Add stock type reference
                $table->foreignId('tipo_stock_id')->after('produto_id')
                      ->nullable()->constrained('tipo_stocks')->onDelete('set null');
                
                // Add stock quantity field
                $table->integer('stock_quantity')->after('quantidade')
                      ->default(0)->comment('Available stock at time of order');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('encomenda_items')) {
            Schema::table('encomenda_items', function (Blueprint $table) {
                $table->dropForeign(['tipo_stock_id']);
                $table->dropColumn(['tipo_stock_id', 'stock_quantity']);
            });
        }
    }
};