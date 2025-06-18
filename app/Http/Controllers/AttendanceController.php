<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Attendance;
use App\models\Event;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Services\AttendanceService;




class AttendanceController extends Controller
{
    public function markAttendance(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $user = $request->user();
        $now = Carbon::now();


        // Check if current time is between 6:00 AM and 11:00 AM
        $startTime = Carbon::today()->setHour(6)->setMinute(0)->setSecond(0);
        $endTime = Carbon::today()->setHour(11)->setMinute(0)->setSecond(0);

        if (!$now->between($startTime, $endTime)) {
            return back()->with('message', 'You can only mark attendance between 6:00 AM and 11:00 AM.');
        }

        // Check if user already marked attendance for this event today
        $alreadyMarked = Attendance::where('user_id', $user->id)
            ->where('event_id', $request->event_id,)
            ->whereDate('attended_at', $now->toDateString())
            ->exists();

        if ($alreadyMarked) {
            return back()->with('message', 'You have already marked attendance for this event today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'event_id' => $request->event_id,
            'attended_at' => now(),
        ]);

        return back()->with('message', 'Attendance marked successfully!');
    }

    public function showAttendanceForm()
    {
        $events = Event::all();
        $surahs = [];

        return view('user.mark_attendance', compact('events', 'surahs'));
    }

    public function markAttendanceViaQR(Event $event)
    {
        $user = auth()->user();
        $now = \Illuminate\Support\Carbon::now();

        $startTime = \Illuminate\Support\Carbon::today()->setHour(6);
        $endTime = \Illuminate\Support\Carbon::today()->setHour(11);

        if (!$now->between($startTime, $endTime)) {
            return redirect()->route('user.dashboard')->with('message', 'Attendance can only be marked between 6 AM and 11 AM.');
        }

        $alreadyMarked = Attendance::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->whereDate('attended_at', $now->toDateString())
            ->exists();

        if ($alreadyMarked) {
            return redirect()->route('user.dashboard')->with('message', 'You have already marked attendance for this event today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'attended_at' => $now,
        ]);

        $user->increment('gems',1);
        
        $this->updateStreakOnAttendance();

        return redirect()->route('user.dashboard')->with('message', 'Attendance marked successfully via QR!');
    }


    public function leaderboard()
    {
        $users = User::where('role', 'user')->with('attendances')->get();

        $leaderboard = $users->map(function ($user) {
            // Get attendance dates in order
            
            $total = app(AttendanceService::class)->getTotalAttendance($user->id);

            return [
                'name' => $user->name,
                'total' => $total,
                'streak' => $user->current_streak,
                'gems' => $user->gems
            ];
        });

        // Sort leaderboard by total or streak
        $leaderboard = $leaderboard->sortByDesc('streak')->values();

        return view('user/leaderboard', compact('leaderboard'));
    }

    public function updateStreakOnAttendance()
    {
        $user = auth()->user();
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Already marked attendance today → skip
        if ($user->last_attendance_date && $user->last_attendance_date->equalTo($today)) {
            return;
        }

        // If streak is frozen → don't reset it
        if ($user->streak_frozen_until && $user->streak_frozen_until->isFuture()) {
            $user->last_attendance_date = $today;
            $user->save();
            return;
        }

        // If last attendance was yesterday → continue the streak
        if ($user->last_attendance_date && $user->last_attendance_date->equalTo($yesterday)) {
            $user->current_streak += 1;
        } else {
            $user->current_streak = 1; // ❗ Reset to 1 because the streak is broken
        }

        $user->last_attendance_date = $today;
        $user->save();
    }


}
