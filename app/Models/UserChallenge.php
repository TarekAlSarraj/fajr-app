<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserChallenge extends Pivot
{
    protected $table = 'challenge_user';

    protected $dates = [
        'completed_at',
        'locked_until',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'challenge_id',
        'approved',
        'completed_at',
        'locked_until',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'locked_until' => 'date'
    ];

     // Define the user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     // Define the challenge relationship
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
