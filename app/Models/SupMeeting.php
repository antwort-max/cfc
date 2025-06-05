<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupMeeting extends Model
{
    protected $table = 'sup_meetings';

    protected $fillable = [
        'supplier_id',
        'date',
        'title',
        'notes',
        'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(SupSupplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
