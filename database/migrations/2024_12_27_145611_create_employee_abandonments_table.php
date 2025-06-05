<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wrk_abandonments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('wrk_employees')->onDelete('cascade'); 
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('finish_time')->nullable();
            $table->string('reason')->nullable();        
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wrk_abandonments');
    }
};
