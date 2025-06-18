<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = [
        'title', 'description', 'reward_gems', 'is_unique', 'lock_days', 'approval_required', 'image_path'
    ];

    public function users()
    {
        // Many-to-many with User via challenge_user pivot table
        return $this->belongsToMany(User::class, 'challenge_user')
                    ->using(UserChallenge::class)
                    ->withPivot(['approved', 'completed_at', 'locked_until'])
                    ->withTimestamps();
    }
}
