<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalFire extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'historical_fires';

    /**
     * Indicates if the model should be timestamped.
     * We don't have created_at/updated_at columns in our import.
     *
     * @var bool
     */
    public $timestamps = false;
}