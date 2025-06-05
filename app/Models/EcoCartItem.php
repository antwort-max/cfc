<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcoCartItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eco_cart_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'sku',
        'name',
        'package_unit',
        'package_qty',
        'package_price',
        'quantity',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'package_qty'   => 'decimal:2',
        'package_price' => 'decimal:2',
        'quantity'      => 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo(EcoCart::class, 'cart_id');
    }

    /**
     * Get the original product reference.
     */
    public function product()
    {
        return $this->belongsTo(PrdProduct::class, 'product_id');
    }
}
