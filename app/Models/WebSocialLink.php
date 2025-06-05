<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebSocialLink extends Model
{
    use HasFactory;

    protected $table = 'web_social_links';

    protected $fillable = [
        'platform',
        'url',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
