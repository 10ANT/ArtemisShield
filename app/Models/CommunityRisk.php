<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityRisk extends Model
{
    use HasFactory;

    protected $table = 'community_risks';

    protected $fillable = [
        'name',
        'state_abbreviation',
        'county_name',
        'population',
        'risk_to_homes_text',
        'whp_text',
        'exposure',
        'latitude',
        'longitude',
    ];
}