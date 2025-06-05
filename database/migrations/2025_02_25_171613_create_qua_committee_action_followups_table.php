<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qua_committee_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->nullable();
            $table->date('followup_date');
            $table->foreign('action_id')->references('id')->on('qua_committee_actions')->onDelete('cascade');
            $table->timestamps();
        });
            
    }

    public function down(): void
    {
        Schema::dropIfExists('qua_committee_followups');
    }
};
