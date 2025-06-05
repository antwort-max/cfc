<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkAbandonment extends Model
{
    use HasFactory;

    protected $table = 'wrk_abandonments';

    protected $fillable = [
        'employee_id',
        'date',
        'start_time',
        'finish_time',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }
}
