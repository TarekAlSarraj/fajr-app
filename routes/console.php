<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ResetBrokenStreaks;
use App\Console\Commands\GenerateAttendanceTokens;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command(ResetBrokenStreaks::class)->dailyAt('00:10');
Schedule::command(GenerateAttendanceTokens::class)->dailyAt('00:10');
