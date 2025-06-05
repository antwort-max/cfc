<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('web_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('order')->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('web_menus')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('web_menus');
    }
};

