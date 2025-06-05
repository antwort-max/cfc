<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qua_nonconformities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('detected_at');
            $table->string('name');
            $table->text('description');
        
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('wrk_areas')->onDelete('cascade');
        
            $table->string('category_severity', 50);
            $table->string('status', 20);
        
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('wrk_employees')->onDelete('cascade');
        
            $table->string('image')->nullable();
            $table->string('attachment')->nullable();
        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qua_nonconformities');
    }
};
