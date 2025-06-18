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
}
