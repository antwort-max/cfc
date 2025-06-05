<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupSupplier extends Model
{
    protected $table = 'sup_suppliers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'brand_code',
        'origin',
        'type',
    ];

    public function brand()
    {
        return $this->belongsTo(PrdBrand::class, 'brand_code', 'code');
    }
}
