<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuaCommitteeMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_date',
        'meeting_time',
        'name',
        'location',
        'agenda',
        'minutes',
    ];

   
}
