<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\PrdCategory;

class WebArea extends Model
{
    use HasFactory;
    protected $table = 'web_areas';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'long_description',
        'image',
        'icon',
    ];

    public function categories(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                PrdCategory::class,
                'web_area_web_category',    
                'web_area_id',              
                'prd_category_id'           
            )
            ->withTimestamps();
    }

}