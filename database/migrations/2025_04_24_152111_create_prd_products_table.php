<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prd_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();

            $table->foreignId('category_id')->constrained('prd_categories')->nullable();
            $table->foreignId('family_id')->constrained('prd_families')->nullable();
            $table->foreignId('subfamily_id')->constrained('prd_subfamilies')->nullable();
            $table->foreignId('brand_id')->constrained('prd_brands')->nullable();

            $table->string('unit')->nullable(); // unidad base, e.g. m2
            $table->decimal('unit_price', 10, 2)->nullable();

            $table->string('package_unit')->nullable(); // e.g. caja
            $table->decimal('package_qty', 8, 2)->nullable(); // cantidad de unidad base por paquete
            $table->decimal('package_price', 10, 2)->nullable();

            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('stock')->default(0);

            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();

            $table->string('image')->nullable();
            $table->boolean('status')->default(true);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->json('tags')->nullable(); // se puede usar como JSON o relacionar con otra tabla

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prd_products');
    }
};
