<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qua_committee_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('responsible_id')->nullable()->constrained('wrk_employees')->onDelete('cascade');
            $table->date('deadline')->nullable();
            $table->string('status', 50)->nullable();
            $table->text('followup_notes')->nullable();
            $table->foreign('meeting_id')->references('id')->on('qua_committee_meetings')->onDelete('cascade');
            $table->timestamps();
        });
            
    }
    
    public function down(): void
    {
        Schema::dropIfExists('qua_committee_actions');
    }
};
