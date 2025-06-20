<?php 

namespace App\Models;

use App\Models\CusCustomer;
use Illuminate\Database\Eloquent\Model;

class WebActivity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'session_id',
        'event_type',
        'event_data',
        'duration_seconds',
        'url',
        'referrer',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'created_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(CusCustomer::class, 'customer_id');
    }

}
