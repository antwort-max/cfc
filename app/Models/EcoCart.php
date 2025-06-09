<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoCart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eco_carts';

    protected $fillable = [
        'customer_id',
        'user_id',
        'guest_token',
        'status',
        'ip_address',
        'explanation',
        'amount',
        'taxes',
        'send_method',
    ];

     // Estados posibles
    public const STATUS_OPEN      = 'open';
    public const STATUS_PENDING   = 'pending';
    public const STATUS_ABANDONED = 'abandoned';
    public const STATUS_CONVERTED = 'converted';


    protected $attributes = [
        'status' => self::STATUS_OPEN,
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'taxes'       => 'decimal:2',
        'send_method' => 'string',
        'status'      => 'string',
        'guest_token' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(CusCustomer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(EcoCartItem::class, 'cart_id');
    }

    public function scopeOpen($q) {
        return $q->where('status', self::STATUS_OPEN);
    }

    public function scopeForCustomer($q, $customerId) {
        return $q->where('customer_id', $customerId);
    }
    
    public function scopeForToken($q, $token) {
        return $q->where('guest_token', $token);
    }

}