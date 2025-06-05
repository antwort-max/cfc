<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrdBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prd_brands';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(PrdProduct::class, 'brand_id');
    }
}
