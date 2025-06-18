<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'start_time', 'end_time'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
