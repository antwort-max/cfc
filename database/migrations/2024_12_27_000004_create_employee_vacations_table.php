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
        Schema::create('wrk_vacations', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('employee_id')->constrained('wrk_employees')->onDelete('cascade'); 
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('days_count')->nullable(); 
            $table->string('status')->default('pending'); 
            $table->text('reason')->nullable();       
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrk_vacations');
    }
};
