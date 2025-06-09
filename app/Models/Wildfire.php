<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wildfire extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'latitude', 'longitude', 'severity', 'status', 
        'started_at', 'contained_at', 'predicted_path', 'affected_area'
    ];

    protected $casts = [
        'predicted_path' => 'array',
        'started_at' => 'datetime',
        'contained_at' => 'datetime'
    ];
}