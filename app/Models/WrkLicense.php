<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkLicense extends Model
{
    use HasFactory;

    protected $table = 'wrk_licenses';

    protected $fillable = [
        'employee_id',
        'date_start',
        'date_finish',
        'attachment',
        'details',
    ];

    protected $casts = [
        'date_start'  => 'date',
        'date_finish' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }
}
