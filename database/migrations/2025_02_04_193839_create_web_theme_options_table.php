<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('web_theme_options', function (Blueprint $table) {
            $table->id();
            $table->enum('theme_mode', ['light', 'dark', 'auto'])->default('light');
            $table->string('font_family')->default('Roboto');
            $table->enum('button_style', ['rounded', 'flat', 'outline'])->default('flat');
            $table->enum('layout_type', ['boxed', 'fullwidth'])->default('fullwidth');
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('web_theme_options');
    }
};