<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qua_committee_meetings', function (Blueprint $table) {
            $table->id();
            $table->date('meeting_date');
            $table->time('meeting_time')->nullable();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->text('agenda')->nullable();
            $table->text('minutes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qua_committee_meetings');
    }
};
