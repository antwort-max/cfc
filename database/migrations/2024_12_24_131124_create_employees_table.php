<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('wrk_employees', function (Blueprint $table) {
            $table->id(); 
            $table->string('name', 255); 
            $table->string('dni', 15)->nullable()->unique(); 
            $table->string('phone', 100)->nullable();
            $table->string('movil', 100)->nullable(); 
            $table->string('email', 100)->nullable(); 
            $table->foreignId('department_id')->nullable()->constrained('wrk_departments')->onDelete('cascade');
            $table->text('details')->nullable();
            $table->foreignId('area_id')->nullable()->constrained('wrk_areas')->onDelete('cascade');           
            $table->date('start')->nullable(); 
            $table->boolean('status')->nullable()->default(1); 
            $table->string('attachment')->nullable(); 
            $table->string('image')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('wrk_employees');
    }
}
