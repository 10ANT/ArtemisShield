<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for simplicity

    protected $casts = [
        'data' => 'array', // Automatically cast the JSON column to/from an array
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that the notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created the original report.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
