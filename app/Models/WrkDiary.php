<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkDiary extends Model
{
    use HasFactory;

    protected $table = 'wrk_diaries';

    protected $fillable = [
        'employee_id',
        'date',
        'name',
        'description',
        'attachment',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }
}
