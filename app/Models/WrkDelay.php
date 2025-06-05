<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class WrkDelay extends Model
{
    use HasFactory;

    protected $casts = [
        'delay_date' => 'date',
    ];

    protected $fillable = [
        'employee_id',
        'delay_date',
        'delay_time',
        'reason',
    ];

    public function getScheduledEntryAttribute(): Carbon
    {
        // Usamos clone() o copy() para no mutar el objeto original
        $date = $this->delay_date->copy();

        return $date->isSaturday()
            ? $date->setTime(9, 30)
            : $date->setTime(9, 0);
    }

    public function employee()
    {
        return $this->belongsTo(WrkEmployee::class, 'employee_id');
    }

    public function getLostMinutesAttribute(): int
{
    $dateISO   = $this->delay_date->toDateString();
    $actual    = Carbon::parse("{$dateISO} {$this->delay_time}");
    $scheduled = $this->scheduled_entry;

    return $actual->greaterThan($scheduled)
        ? $actual->diffInMinutes($scheduled)
        : 0;
}
}
