<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuaCommitteeAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'description',
        'responsible_id',
        'deadline',
        'status',
        'followup_notes',
    ];

    // 🔁 Relaciones

    public function meeting()
    {
        return $this->belongsTo(QuaCommitteeMeeting::class);
    }

    public function responsible()
    {
        return $this->belongsTo(WrkEmployee::class, 'responsible_id');
    }
}
