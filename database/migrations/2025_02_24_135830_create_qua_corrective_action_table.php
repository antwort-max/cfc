<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qua_corrective_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nonconformity_id'); 
            $table->text('description');   
            $table->unsignedBigInteger('responsible_id')->nullable()->constrained('wrk_employees')->onDelete('cascade');                   
            $table->date('start_date')->nullable();           
            $table->date('end_date')->nullable();             
            $table->string('status', 20);                     
            $table->timestamps();
            $table->foreign('nonconformity_id')->references('id')->on('qua_nonconformities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qua_corrective_actions');
    }
};
