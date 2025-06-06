<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('web_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id')->index();
            $table->string('event_type'); // Ej: 'visit_route', 'click', 'add_to_cart'
            $table->json('event_data')->nullable(); // Detalles como producto, búsqueda, etc.
            $table->integer('duration_seconds')->nullable(); // Tiempo en la página
            $table->string('url');
            $table->string('referrer')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_activities');
    }
};
