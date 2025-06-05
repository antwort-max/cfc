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
        Schema::create('eco_cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id')->comment('FK a eco_carts');
            $table->unsignedBigInteger('product_id')->comment('FK a prd_products');

            // Snapshot de datos
            $table->string('sku')->comment('SKU del producto');
            $table->string('name')->comment('Nombre del producto');
            $table->string('package_unit')->nullable()->comment('Unidad de paquete, ej. caja');
            $table->decimal('package_qty', 12, 2)->nullable()->comment('Cantidad de unidades base por paquete');
            $table->decimal('package_price', 12, 2)->comment('Precio por paquete');

            // Cantidad: paquetes * package_qty
            $table->decimal('quantity', 12, 2)->comment('Cantidad total de unidades');

            $table->timestamps();

            // Índices y claves
            $table->index('cart_id', 'idx_cart_items_cart');
            $table->index(['cart_id', 'product_id'], 'idx_cart_items_product');
            $table->foreign('cart_id')
                  ->references('id')->on('eco_carts')
                  ->onDelete('cascade');
            $table->foreign('product_id')
                  ->references('id')->on('prd_products')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_cart_items');
    }
};
