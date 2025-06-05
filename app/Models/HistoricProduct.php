<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricProduct extends Model
{
    use HasFactory;

    protected $table = 'historic_products';

    protected $fillable = [
        'document_id',
        'product_id',
        'document_date',
        'document_type',
        'document_number',
        'product_sku',
        'product_name',
        'product_unit',
        'product_cost',
        'product_price',
        'warehouse',
        'quantity',
        'total_sales_amount',
    ];

    protected $casts = [
        'document_date' => 'date',
        'product_cost' => 'float',
        'product_price' => 'float',
        'quantity' => 'integer',
        'total_sales_amount' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(PrdProduct::class, 'product_sku', 'sku');
    }

}
