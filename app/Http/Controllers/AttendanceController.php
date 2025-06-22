<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\DB;




class AttendanceController extends Controller
{
    
public function submit(Request $request)
{

    $user = $request->user();
    $now = now();

    // Time restriction: only between 6 AM - 10 AM
    $startTime = Carbon::today()->setHour(6);
    $endTime = Carbon::today()->setHour(10);
    if (!$now->between($startTime, $endTime)) {
        return back()->with('message', 'يمكنك تسجيل الحضور بين الساعة 6 صباحًا و10 صباحًا فقط.');
    }

    // Prevent multiple attendance for same event same day
    $alreadyMarked = Attendance::where('user_id', $user->id)
        ->where('event_id', $request->event_id)
        ->whereDate('attended_at', $now->toDateString())
        ->exists();

    if ($alreadyMarked) {
        return back()->with('message', 'لقد قمت بالفعل بتسجيل الحضور لهذا الموعد اليوم.');
    }

    // Determine gems reward, double if checkbox checked
    $gemsReward = $request->boolean('arrived_early') ? 2 : 1;
    // Start transaction
    DB::transaction(function () use ($user, $request, $now, $gemsReward) {
        // Create attendance
        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'event_id' => $request->event_id,
            'attended_at' => $now,
        ]);

        // Save all recitations linked to attendance
        foreach ($request->recitations as $recitation) {
            $attendance->recitations()->create([
                'surah_name' => $recitation['surah_name'],
                'from_verse' => $recitation['from_verse'],
                'to_verse' => $recitation['to_verse'],
            ]);
        }

        // Add gems to user
        $user->increment('gems', $gemsReward);
        $this->updateStreakOnAttendance();
        $user->last_attendance_date = now()->startOfDay();
        $user->save();
    });

    return back()->with('success', "تم تسجيل الحضور بنجاح! وتم إضافة {$gemsReward} 💎 إلى رصيدك.");
}


    public function showAttendanceForm($token)
    {
        $user = auth()->user();

        // Check if the logged-in user's token matches the token in the URL and it's not expired
        if (!$user || $user->attendance_token !== $token || !$user->attendance_token_expires_at || now()->gt($user->attendance_token_expires_at)) {
            abort(403, 'Unauthorized access to attendance form.');
        }

        $events = Event::all();
        $surahs = config('surahs');

        return view('user.attendance', compact('user', 'events', 'surahs'));
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
