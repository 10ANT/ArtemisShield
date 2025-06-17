<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    /**
     * The primary key for the model is not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * We don't have created_at/updated_at columns in this table.
     *
     * @var bool
     */
    public $timestamps = false;
}