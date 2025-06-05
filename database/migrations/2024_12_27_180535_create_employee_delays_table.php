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
        Schema::create('wrk_delays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('wrk_employees')->onDelete('cascade'); 
            $table->date('delay_date')->nullable();
            $table->time('delay_time')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrk_delays');
    }
};
