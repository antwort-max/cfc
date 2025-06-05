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
        Schema::create('bi_daily_stock_snapshots', function (Blueprint $table) {
            // Clave compuesta snapshot_date + product_sku
            $table->date('snapshot_date');
            $table->string('product_sku', 50);

            // Datos de stock
            $table->decimal('stock', 12, 3);
            $table->decimal('cost', 12, 5);
            $table->decimal('price', 12, 5);

            // Valor calculado (stock × price)
            $table->decimal('value', 14, 2)
                ->storedAs('stock * cost');

            // Timestamps para control de inserción/actualización
            $table->timestamps();

            // Índices y clave primaria
            $table->primary(['snapshot_date', 'product_sku']);
            $table->index('snapshot_date', 'idx_snapshot_date');
            $table->index('product_sku',   'idx_product_sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bi_daily_stock_snapshots');
    }
};
