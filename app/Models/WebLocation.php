<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WebLocation extends Model
{
    protected $table = 'web_locations';

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'latitude',
        'longitude',
        'type',
        'working_hours',
        'description',
        'image',
    ];

    protected $casts = [
        'working_hours' => 'array',  
        'latitude'      => 'float',
        'longitude'     => 'float',
    ];

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeNearby(Builder $query, float $lat, float $lng, int $radiusKm = 5): Builder
    {
        $haversine = "(6371 * acos(cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))))";

        return $query
            ->selectRaw("* , {$haversine} AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radiusKm);
    }

   
}
