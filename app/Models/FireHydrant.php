<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireHydrant extends Model
{
    use HasFactory;

    protected $primaryKey = 'osm_id'; // Specify primary key
    public $incrementing = false; // osm_id is not auto-incrementing
    protected $keyType = 'string'; // osm_id can be large, treat as string or unsigned big int

    protected $fillable = [
        'osm_id', 'type', 'lat', 'lon', 'emergency', 'fire_hydrant_type',
        'fire_hydrant_diameter', 'operator', 'colour', 'color', 'ref',
        'description', 'addr_street', 'addr_housenumber', 'addr_city',
        'addr_postcode', 'addr_state', 'source', 'survey_date',
        'fire_hydrant_position', 'fire_hydrant_pressure', 'access',
        'note', 'water_source', 'osm_timestamp', 'osm_version',
        'osm_changeset', 'osm_user', 'osm_uid', 'all_tags'
    ];

    protected $casts = [
        'all_tags' => 'array', // Cast the 'all_tags' JSON column to an array
        'lat' => 'float',
        'lon' => 'float',
        'osm_timestamp' => 'datetime',
    ];
}