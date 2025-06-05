<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDiariesTable extends Migration
{

    public function up()
    {
        Schema::create('wrk_diaries', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('employee_id')->constrained('wrk_employees')->onDelete('cascade'); 
            $table->date('date')->nullable(); 
            $table->string('name')->nullable(); 
            $table->text('description')->nullable(); 
            $table->string('attachment')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('wrk_diaries');
    }
}
