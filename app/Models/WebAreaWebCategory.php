<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WebAreaWebCategory extends Pivot
{
    
    protected $table = 'web_area_web_category';
    public $timestamps = true;

    protected $fillable = [
        'web_area_id',
        'prd_category_id',
    ];
}
