<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebFooterSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
