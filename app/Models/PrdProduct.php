<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrdProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prd_products';

    protected $fillable = [
        'sku',
        'name',
        'slug',
        
        'category_id',
        'family_id',
        'subfamily_id',
        'brand_id',
        
        'unit',
        'unit_price',
        'previous_price',
        
        'package_unit',
        'package_qty',
        'package_price',

        'description',
        'cost',
        'stock',

        'weight',
        'dimensions',
            
        'image',
        'status',

        'meta_title',
        'meta_description',
        'meta_keywords',
        'tags'
        
    ];

    // Relaciones
    public function family()
    {
        return $this->belongsTo(PrdFamily::class, 'family_id');
    }

    public function subfamily()
    {
        return $this->belongsTo(PrdSubfamily::class, 'subfamily_id');
    }

    public function category()
    {
        return $this->belongsTo(PrdCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(PrdBrand::class, 'brand_id');
    }

    public function historicProducts()
    {
        return $this->hasMany(HistoricProduct::class, 'product_sku', 'sku');
    }

}
