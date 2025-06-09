<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('web_area_web_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_area_id')
                  ->constrained('web_areas')
                  ->onDelete('cascade');
            $table->foreignId('prd_category_id')
                  ->constrained('prd_categories')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('web_area_web_category');
    }
};
