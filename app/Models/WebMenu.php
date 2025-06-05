<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'parent_id',
        'url',
        'icon',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // 🔁 Relaciones jerárquicas
    public function parent()
    {
        return $this->belongsTo(WebMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(WebMenu::class, 'parent_id');
    }
}
