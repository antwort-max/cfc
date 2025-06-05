<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuaNonconformity extends Model
{
    use HasFactory;

    protected $fillable = [
        'detected_at',
        'name',
        'description',
        'area_id',
        'category_severity',
        'status',
        'employee_id',
        'image',
        'attachment',
    ];

    // Relaciones
    public function area()
    {
        return $this->belongsTo(\App\Models\WrkArea::class);
    }

    public function employee()
    {
        return $this->belongsTo(\App\Models\WrkEmployee::class);
    }
}
