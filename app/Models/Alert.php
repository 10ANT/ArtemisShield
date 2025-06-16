<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

     protected $fillable = [
        'category',
        'location_description',
        'latitude',
        'longitude',
        'severity',
        'status',
        'victims',
    ];

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
}
