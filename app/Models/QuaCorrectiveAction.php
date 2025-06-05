<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuaCorrectiveAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nonconformity_id',
        'description',
        'responsible_id',
        'start_date',
        'end_date',
        'status',
    ];

    // 🔁 Relaciones

    public function nonconformity()
    {
        return $this->belongsTo(QuaNonconformity::class);
    }

    public function responsible()
    {
        return $this->belongsTo(WrkEmployee::class, 'responsible_id');
    }
}
