<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cus_customers', function (Blueprint $table) {

            /* ---------- Credenciales y recordatorio ---------- */
            if (!Schema::hasColumn('cus_customers', 'password')) {
                $table->string('password')->after('email');
            }

            if (!Schema::hasColumn('cus_customers', 'remember_token')) {
                $table->rememberToken()->after('password');
            }

            if (!Schema::hasColumn('cus_customers', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('password');
            }

            /* ---------- Métricas de actividad ---------- */
            if (!Schema::hasColumn('cus_customers', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('email_verified_at')->index();
            }

            if (!Schema::hasColumn('cus_customers', 'last_purchase_at')) {
                $table->timestamp('last_purchase_at')->nullable()->after('last_login_at')->index();
            }

            /* ---------- Identificación fiscal ---------- */
            if (!Schema::hasColumn('cus_customers', 'billing_rut')) {
                $table->string('billing_rut')->nullable()->after('rut');
                $table->char('billing_rut_dv', 1)->nullable()->after('billing_rut');
                $table->index(['billing_rut', 'billing_rut_dv']);
            }

            if (!Schema::hasColumn('cus_customers', 'billing_name')) {
                $table->string('billing_name')->nullable()->after('company');
            }

            if (!Schema::hasColumn('cus_customers', 'giro')) {
                $table->string('giro')->nullable()->after('billing_name');
            }

            if (!Schema::hasColumn('cus_customers', 'is_tax_exempt')) {
                $table->boolean('is_tax_exempt')->default(false)->after('status');
            }

            /* ---------- Domicilio de facturación ---------- */
            $addressFields = [
                'billing_address_street', 'billing_address_number',
                'billing_address_commune', 'billing_address_city',
                'billing_address_region', 'billing_address_zip',
                'billing_country'
            ];

            foreach ($addressFields as $field) {
                if (!Schema::hasColumn('cus_customers', $field)) {
                    $type = $field === 'billing_address_number' ? 'string' : 'string';
                    $table->$type($field, $field === 'billing_address_number' ? 10 : 255)
                          ->nullable()
                          ->after('giro');
                }
            }

            /* ---------- Contacto de facturación ---------- */
            if (!Schema::hasColumn('cus_customers', 'billing_phone')) {
                $table->string('billing_phone', 30)->nullable()->after('billing_country');
            }

            if (!Schema::hasColumn('cus_customers', 'billing_email')) {
                $table->string('billing_email')->nullable()->after('billing_phone');
                $table->index('billing_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cus_customers', function (Blueprint $table) {
            $table->dropIndex(['billing_email']);
            $table->dropIndex(['billing_rut', 'billing_rut_dv']);

            $table->dropColumn([
                // Contacto facturación
                'billing_email', 'billing_phone',

                // Domicilio facturación
                'billing_country', 'billing_address_zip', 'billing_address_region',
                'billing_address_city', 'billing_address_commune',
                'billing_address_number', 'billing_address_street',

                // Identificación fiscal
                'is_tax_exempt', 'giro', 'billing_name',
                'billing_rut_dv', 'billing_rut',

                // Métricas
                'last_purchase_at', 'last_login_at',

                // Credenciales
                'email_verified_at', 'remember_token', 'password',
            ]);
        });
    }
};

