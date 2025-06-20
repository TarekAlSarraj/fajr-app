<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recitation extends Model
{

    protected $fillable = ['attendance_id', 'surah_name', 'from_verse', 'to_verse'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
