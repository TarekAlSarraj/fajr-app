<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'event_id', 'attended_at'];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class); // Replace YourClassModel with your actual Class model
    }

     public function recitations()
    {
        return $this->hasMany(Recitation::class);
    }
    
}
