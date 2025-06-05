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
        Schema::create('historic_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_id');
            $table->date('document_date')->index();
            $table->time('document_time');
            $table->string('document_type', 10)->index();
            $table->string('document_number', 20);
            $table->string('client')->nullable();
            $table->string('place', 10)->nullable();
            $table->string('seller', 10)->nullable();
            $table->integer('total_sales_amount')->nullable();
            $table->integer('total_sales_amount_with_tax')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historic_documents');
    }
};
