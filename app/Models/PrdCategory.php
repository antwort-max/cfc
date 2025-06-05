<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrdCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prd_categories';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'image',
        'subfamily_id',
    ];

    public function subfamily()
    {
        return $this->belongsTo(PrdSubfamily::class, 'subfamily_id');
    }

    public function products()
    {
        return $this->hasMany(PrdProduct::class, 'category_id');
    }
}
