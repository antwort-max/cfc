<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrdSubfamily extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prd_subfamilies';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'image',
        'family_id',
    ];

    public function family()
    {
        return $this->belongsTo(PrdFamily::class, 'family_id');
    }

    public function categories()
    {
        return $this->hasMany(PrdCategory::class, 'subfamily_id');
    }

    public function products()
    {
        return $this->hasMany(PrdProduct::class, 'subfamily_id');
    }
}
