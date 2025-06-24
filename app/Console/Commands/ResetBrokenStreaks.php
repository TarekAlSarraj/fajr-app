<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetBrokenStreaks extends Command
{
    protected $signature = 'streaks:reset-broken';
    protected $description = 'Reset streaks of users who missed attending and did not freeze their streak.';

    public function handle()
    {
        $today = now()->startOfDay();

        User::where(function ($query) use ($today) {
                $query->whereNull('last_attendance_date')
                    ->orWhereDate('last_attendance_date', '<', $today);
            })
            ->where(function ($query) {
                $query->whereNull('streak_frozen_until')
                    ->orWhere('streak_frozen_until', '<', now());
            })
            ->update(['current_streak' => 0]);

        $this->info('Streaks reset for users who didn’t attend today.');
    }

}

