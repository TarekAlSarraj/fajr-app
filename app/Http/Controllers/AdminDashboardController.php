<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Challenge;
use App\Models\Purchase;
use App\Models\UserChallenge;
use App\Models\User;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['user', 'event', 'recitations'])->latest()->get();
        $challenges = Challenge::with('users')->latest()->get();
        $userChallenges = UserChallenge::with(['user', 'challenge'])->get();
        $purchases = Purchase::with('user')->latest()->get();

        // Leaderboard Query
        $leaderboard = User::where('role','user')->withCount(['attendances as total_attendance_days'])
            ->orderByDesc('total_attendance_days')
            ->get(['id', 'name', 'current_streak', 'gems', 'total_attendance_days']);

        return view('admin.dashboard', compact('attendances', 'userChallenges', 'purchases', 'leaderboard'));
    }

    public function approve_challenge(UserChallenge $userChallenge)
    {
        if (!$userChallenge->approved) {
            $userChallenge->approved = true;
            $userChallenge->save();

            $userChallenge->user->increment('gems', $userChallenge->challenge->reward_gems);
        }

        return back()->with('message', 'تمت الموافقة وإضافة الجواهر ✅');
    }

}
