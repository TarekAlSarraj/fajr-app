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
        $yesterday = now()->subDay()->startOfDay();

        User::whereDate('last_attendance_date', '<', $yesterday)
            ->where(function ($query) {
                $query->whereNull('streak_frozen_until')
                      ->orWhere('streak_frozen_until', '<', now());
            })
            ->update(['current_streak' => 0]);

        $this->info('Broken streaks reset.');
    }
}

