<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebThemeOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'icon',
        'theme_mode',
        'font_family',
        'button_style',
        'layout_type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
