<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('sup_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('brand_code'); 
            $table->enum('origin', ['nacional', 'importacion'])->default('nacional');
            $table->enum('type', ['productos', 'insumos', 'activos', 'servicios'])->default('productos')->change();
            $table->timestamps();
            $table->foreign('brand_code')->references('code')->on('prd_brands')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sup_suppliers');
    }
};
