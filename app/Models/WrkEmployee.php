<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WrkEmployee extends Model
{
    use HasFactory;

    protected $table = 'wrk_employees';

    protected $fillable = [
        'name',
        'dni',
        'phone',
        'movil',
        'email',
        'department_id',
        'details',
        'area_id',
        'code',
        'start',
        'status',
        'attachment',
        'image',
    ];

    protected $casts = [
        'start'  => 'date',
        'status' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(WrkDepartment::class, 'department_id');
    }

    public function area()
    {
        return $this->belongsTo(WrkArea::class, 'area_id');
    }
}
