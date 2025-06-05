php artisan make:migration create_web_locations_table --create=web_locations
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('web_locations', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('address');   
            $table->string('city');       
            $table->string('phone', 20)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('type', ['Local', 'Bodega', 'Oficina'])->default('Local');
            $table->json('working_hours')->nullable(); 
            $table->text('description')->nullable();
            $table->string('image')->nullable()->comment('Storage path or URL');
            $table->timestamps();
            $table->index(['city', 'type']);
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_locations');
    }
};
