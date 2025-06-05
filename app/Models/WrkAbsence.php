<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkAbsence extends Model
{
    use HasFactory;

    protected $table = 'wrk_absences';

    protected $fillable = [
        'employee_id',
        'date',
        'reason',
        'discount',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }
}
