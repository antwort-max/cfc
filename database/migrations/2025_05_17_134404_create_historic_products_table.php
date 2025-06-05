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
        Schema::create('historic_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->date('document_date')->index();
            $table->string('document_type', 10);
            $table->string('document_number', 20);
            $table->string('product_sku', 50)->index();
            $table->string('product_name', 255);
            $table->string('product_unit', 10);
            $table->decimal('product_cost', 12, 5);
            $table->decimal('product_price', 12, 5);
            $table->string('warehouse', 10);
            $table->integer('quantity');
            $table->integer('total_sales_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historic_products');
    }
};
