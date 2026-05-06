<?php

use App\Console\Commands\FetchJokesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the jokes fetching command to run every 5 minutes
Schedule::command(FetchJokesCommand::class, ['--count' => 1])
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/jokes-fetch.log'))
    ->description('Fetch jokes from API every 5 minutes');
