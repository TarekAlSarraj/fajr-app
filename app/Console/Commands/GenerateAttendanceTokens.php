<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class GenerateAttendanceTokens extends Command
{
    protected $signature = 'attendance:generate-tokens';
    protected $description = 'Generate daily attendance tokens for all users';

    public function handle()
    {
        $expiration = now()->endOfDay();

        User::each(function ($user) use ($expiration) {
            $user->attendance_token = Str::random(32);
            $user->attendance_token_expires_at = $expiration;
            $user->save();
        });

        $this->info('✅ Attendance tokens generated successfully.');
    }
}
