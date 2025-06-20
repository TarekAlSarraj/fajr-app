<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\User;

class AttendanceService
{

    /**
     * Get the total attendance of the user per event
     *
     * @param int $user_id
     * @return int
     */
    public function getTotalAttendance(int $user_id): int{
        $totalAttendances = \App\Models\Attendance::where('user_id', $user_id)
            ->where('event_id', 1) //here to be edited for future
            ->count();
            
        return $totalAttendances;
    }
    
}
