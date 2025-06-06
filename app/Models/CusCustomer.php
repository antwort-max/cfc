<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;   // ← ahora es autenticable
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CusCustomer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /** Tabla física */
    protected $table = 'cus_customers';

    /** Asignación masiva */
    protected $fillable = [
        // Credenciales / sistema
        'email',
        'password',
        'status',

        // Identidad y contacto
        'user_id',          // opcional: quítalo si no lo usas
        'rut',
        'first_name',
        'last_name',
        'phone',
        'mobile',
        'company',
        'address_street',
        'address_city',
        'address_region',
        'address_zip',
        'notes',

        // Facturación
        'billing_rut',
        'billing_rut_dv',
        'billing_name',
        'giro',
        'is_tax_exempt',
        'billing_address_street',
        'billing_address_number',
        'billing_address_commune',
        'billing_address_city',
        'billing_address_region',
        'billing_address_zip',
        'billing_country',
        'billing_phone',
        'billing_email',

        // Métricas de actividad
        'last_login_at',
        'last_purchase_at',
    ];

    /** Campos ocultos en arrays/JSON */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'last_purchase_at'  => 'datetime',
        'is_tax_exempt'     => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
