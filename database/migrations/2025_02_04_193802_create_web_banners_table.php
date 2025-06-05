<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('web_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('link')->nullable();
            $table->enum('position', ['home', 'category', 'cart'])->default('home');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('web_banners');
    }
};
