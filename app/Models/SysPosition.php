<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SysPosition extends Model
{
    protected $table = 'sys_positions';   // asegúrate que sea plural
    protected $fillable = ['name', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'position_id');
    }
}
