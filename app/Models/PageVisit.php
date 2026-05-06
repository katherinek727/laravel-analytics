<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'visitor_id',
        'ip_address',
        'country',
        'city',
        'region',
        'latitude',
        'longitude',
        'user_agent',
        'browser',
        'browser_version',
        'platform',
        'device_type',
        'is_mobile',
        'is_tablet',
        'is_desktop',
        'is_bot',
        'url',
        'path',
        'referrer',
        'referrer_domain',
        'screen_width',
        'screen_height',
        'language',
        'timezone',
        'page_load_time',
        'visited_at',
        'left_at',
        'duration',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_mobile' => 'boolean',
        'is_tablet' => 'boolean',
        'is_desktop' => 'boolean',
        'is_bot' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'visited_at' => 'datetime',
        'left_at' => 'datetime',
        'page_load_time' => 'integer',
        'duration' => 'integer',
        'screen_width' => 'integer',
        'screen_height' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @return array<int, string>
     */
    protected function hidden(): array
    {
        return [
            'user_agent',
            'ip_address',
            'session_id',
            'visitor_id',
        ];
    }

    /**
     * Scope a query to only include visits from a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?? now();
        return $query->whereBetween('visited_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include unique visitors.
     */
    public function scopeUniqueVisitors($query)
    {
        return $query->select('visitor_id')
            ->selectRaw('COUNT(*) as visit_count')
            ->groupBy('visitor_id');
    }

    /**
     * Scope a query to only include visits from a specific country.
     */
    public function scopeFromCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to only include visits from a specific device type.
     */
    public function scopeDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Get the visit duration in human readable format.
     */
    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration) {
            return 'N/A';
        }

        if ($this->duration < 60) {
            return "{$this->duration} seconds";
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        if ($minutes < 60) {
            return $seconds > 0 
                ? "{$minutes}m {$seconds}s" 
                : "{$minutes} minutes";
        }

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return $minutes > 0 
            ? "{$hours}h {$minutes}m" 
            : "{$hours} hours";
    }

    /**
     * Get the visit date in a formatted string.
     */
    public function getVisitedDateAttribute(): string
    {
        return $this->visited_at->format('Y-m-d');
    }

    /**
     * Get the visit time in a formatted string.
     */
    public function getVisitedTimeAttribute(): string
    {
        return $this->visited_at->format('H:i:s');
    }

    /**
     * Get the visit hour for grouping.
     */
    public function getVisitedHourAttribute(): int
    {
        return (int) $this->visited_at->format('H');
    }
}
