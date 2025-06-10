<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireStation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'osm_id',
        'type',
        'lat',
        'lon',
        'amenity',
        'name',
        'official_name',
        'alt_name',
        'operator',
        'operator_type',
        'fire_station_type',
        'addr_street',
        'addr_housenumber',
        'addr_city',
        'addr_postcode',
        'addr_state',
        'addr_country',
        'phone',
        'emergency',
        'website',
        'email',
        'opening_hours',
        'contact_phone',
        'contact_website',
        'contact_email',
        'source',
        'building',
        'building_levels',
        'ref',
        'ref_nfirs',
        'fire_station_code',
        'description',
        'wheelchair',
        'access',
        'note',
        'wikidata',
        'wikipedia',
        'fire_station_apparatus',
        'fire_station_staffing',
        'all_tags', // Don't forget this if you're importing it
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'all_tags' => 'array', // Cast 'all_tags' to array/JSON automatically
    ];
}