<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all challenges with user-specific pivot data (if exists)
        $challenges = Challenge::all()->map(function ($challenge) use ($user) {
            // Get latest submission for this challenge by this user
            $pivot = $user->challenges()->wherePivot('challenge_id', $challenge->id)->latest()->first();

            $canAttempt = true;
            $lockedUntil = $pivot?->locked_until;

            if ($challenge->is_unique && $pivot) {
                $canAttempt = false;
            }

            if ($lockedUntil && now()->lt($lockedUntil)) {
                $canAttempt = false;
            }

            return (object)[
                'id'            => $challenge->id,
                'title'          => $challenge->title,
                'description'   => $challenge->description,
                'reward_gems'        => $challenge->reward_gems,
                'is_unique'     => $challenge->is_unique,
                'lock_days'     => $challenge->lock_days,
                'can_attempt'   => $canAttempt,
                'locked_until'  => $lockedUntil,
                'approved'   => $pivot?->approved,
                'submitted_at'  => $pivot?->completed_at,
                'image_path' => $challenge->image_path
            ];
        });

        $userChallenges = $user->userChallenges()->get();

        return view('user.challenges', compact('userChallenges', 'challenges'));

    }

    public function submit(Request $request, Challenge $challenge)
    {
        $user = auth()->user();

        // Check if already completed and is unique
        $existing = $user->challenges()->wherePivot('challenge_id', $challenge->id)->latest()->first();

        if ($challenge->is_unique && $existing) {
            return back()->with('error', 'لا يمكنك تكرار هذا التحدي.');
        }

        // Check if locked
        if ($existing && $existing->pivot->locked_until && now()->lt($existing->pivot->locked_until)) {
            return back()->with('error', 'هذا التحدي مقفل حتى ' . $existing->pivot->locked_until->translatedFormat('Y-m-d H:i'));
        }

        // Calculate locked_until for this attempt
        $lockedUntil = $challenge->lock_days > 0 ? now()->addDays($challenge->lock_days) : null;

        // Attach record (approval pending)
        $user->challenges()->attach($challenge->id, [
            'completed_at' => now(),
            'locked_until' => $lockedUntil,
            'approved'  => null,
        ]);

        return back()->with('success', 'تم إرسال التحدي للمراجعة.');
    }



}
