<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkVacation extends Model
{
    use HasFactory;

    protected $table = 'wrk_vacations';

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'days_count',
        'status',
        'reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'days_count' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }
}
