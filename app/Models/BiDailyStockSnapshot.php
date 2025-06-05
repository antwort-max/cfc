<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiDailyStockSnapshot extends Model
{
    use HasFactory;

    protected $table = 'bi_daily_stock_snapshots';

    public $incrementing = false;

    protected $primaryKey = null;

    protected $keyType = 'string';

    protected $fillable = [
        'snapshot_date',
        'product_sku',
        'stock',
        'cost',
        'price',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'stock'         => 'decimal:3',
        'cost'          => 'decimal:5',
        'price'         => 'decimal:5',
        'value'         => 'decimal:2', 
    ];
}
