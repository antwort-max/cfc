<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuaCommitteeFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_id',
        'notes',
        'status',
        'followup_date',
    ];

    public function action()
    {
        return $this->belongsTo(QuaCommitteeAction::class);
    }
}
