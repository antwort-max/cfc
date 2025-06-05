<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrdFamily extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prd_families';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'image',
    ];

    public function subfamilies()
    {
        return $this->hasMany(PrdSubfamily::class, 'family_id');
    }

    public function products()
    {
        return $this->hasMany(PrdProduct::class, 'family_id');
    }
}