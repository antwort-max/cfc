<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCusCustomersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cus_customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('FK opcional a users.id');
            $table->string('rut', 12)->unique()->comment('RUT o DNI del cliente');
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('company', 100)->nullable();
            $table->string('address_street', 150)->nullable();
            $table->string('address_city', 60)->nullable();
            $table->string('address_region', 60)->nullable();
            $table->string('address_zip', 20)->nullable();
            $table->text('notes')->nullable()->comment('Observaciones internas / CRM');
            $table->enum('status', ['prospect','active','inactive','blocked'])->default('prospect');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cus_customers');
    }
}
