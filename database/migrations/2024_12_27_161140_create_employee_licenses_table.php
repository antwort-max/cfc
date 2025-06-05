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
        Schema::create('wrk_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('wrk_employees')->onDelete('cascade'); 
            $table->date('date_start')->nullable();
            $table->date('date_finish')->nullable();
            $table->string('attachment')->nullable();
            $table->text('details')->nullable();        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrk_licenses');
    }
};
