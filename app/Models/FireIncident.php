<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude', 'longitude', 'brightness', 'scan', 'track',
        'acq_date', 'acq_time', 'satellite', 'instrument', 'confidence',
        'version', 'bright_t31', 'frp', 'daynight', 'type', 'source'
    ];

    protected $casts = [
        'acq_date' => 'date',
        'acq_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'brightness' => 'decimal:2',
        'frp' => 'decimal:2',
    ];

    public function getConfidenceLevelAttribute()
    {
        if ($this->confidence >= 80) return 'high';
        if ($this->confidence >= 50) return 'medium';
        return 'low';
    }

    public function getIntensityColorAttribute()
    {
        if ($this->brightness >= 400) return '#FF0000'; // Red
        if ($this->brightness >= 350) return '#FF4500'; // Orange Red
        if ($this->brightness >= 300) return '#FFA500'; // Orange
        return '#FFFF00'; // Yellow
    }
}