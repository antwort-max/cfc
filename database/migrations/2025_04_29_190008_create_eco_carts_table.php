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
        Schema::create('eco_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable()->comment('FK a cus_customers');
            $table->unsignedBigInteger('user_id')->nullable()->comment('FK opcional a users.id');
            $table->enum('status', ['open','converted','abandoned'])->default('open');
            $table->string('ip_address', 45)->nullable()->comment('IP para carritos anónimos');
            $table->string('guest_token', 36)->nullable()->after('ip_address')->index();
            $table->text('explanation')->nullable()->comment('Explicación o nota del carrito');
            $table->decimal('amount', 12, 2)->default(0)->comment('Monto total del carrito');
            $table->decimal('taxes', 12, 2)->default(0)->comment('Impuestos asociados');
            $table->enum('send_method', ['email','printed','whatsapp'])->nullable()->comment('Método de envío del resumen');
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id', 'idx_eco_carts_customer');
            $table->index('user_id',     'idx_eco_carts_user');
            $table->index('status',      'idx_eco_carts_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eco_carts');
    }
};
