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
        Schema::create('wrk_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Trabajador asignado
            $table->unsignedBigInteger('area_id')->nullable(); // Área a la que pertenece
        
            $table->string('title');
            $table->text('description')->nullable();
        
            $table->enum('priority', ['alta', 'media', 'baja'])->default('media');
            $table->enum('status', ['pendiente', 'en_progreso', 'completada'])->default('pendiente');
            $table->date('due_date')->nullable();
        
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('wrk_areas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrk_tasks');
    }
};
