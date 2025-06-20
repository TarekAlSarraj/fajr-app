<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
     

    public function index()
    {
    $user = auth()->user();

    $currentStreak = $user->current_streak;
    $lastClaimed = $user->last_claimed_streak ?? 0;
    $milestones = config('streak_milestones.milestones');
    // Only show reward if current streak is a milestone AND user hasn't claimed it yet
    if (array_key_exists($currentStreak, $milestones) && $currentStreak > $lastClaimed) {
        $nextClaim = [
            'days' => $currentStreak,
            'reward' => $milestones[$currentStreak],
        ];
    } else {
        $nextClaim = null;
    }

    return view('user.dashboard', compact('user', 'nextClaim'));
    }

public function claimReward($days)
{
    $user = auth()->user();

    $milestones = config('streak_milestones.milestones');

    if (!array_key_exists($days, $milestones)) {
        return back()->with('error', 'غير صالح.');
    }

    if ($user->last_claimed_streak >= $days) {
        return back()->with('error', 'لقد استلمت هذه المكافأة بالفعل.');
    }

    if ($user->current_streak < $days) {
        return back()->with('error', 'لا يمكنك المطالبة بهذه المكافأة الآن.');
    }

    // Give gems reward and update last claimed streak
    $user->increment('gems', $milestones[$days]);
    $user->last_claimed_streak = $days;
    $user->save();

    return back()->with('message', 'تم استلام المكافأة بنجاح!');
}

}
