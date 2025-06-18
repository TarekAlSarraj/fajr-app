<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'gems'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['streak_frozen_until', 'last_attendance_date'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'streak_frozen_until' => 'datetime',
            'last_attendance_date' => 'date',
        ];
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function dashboardRouteName(): string
    {
        return $this->role === 'admin' ? 'admin.dashboard' : 'user.dashboard';
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function challenges()
    {
        // Many-to-many with Challenge via challenge_user pivot table
        return $this->belongsToMany(Challenge::class, 'challenge_user')
                    ->using(UserChallenge::class) // Optional: if you have a custom pivot model
                    ->withPivot(['approved', 'completed_at', 'locked_until'])
                    ->withTimestamps();
    }

    // In App\Models\User.php

    public function userChallenges()
    {
        return $this->belongsToMany(Challenge::class, 'challenge_user')
                    ->withPivot(['locked_until', 'approved', 'created_at', 'updated_at'])
                    ->withTimestamps();
    }


}
