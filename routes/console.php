<?php

use App\Console\Commands\FetchCurrencyRates;
use App\Console\Commands\OptimizeMedia;
use App\Console\Commands\ProcessRecurringDonations;
use Illuminate\Support\Facades\Schedule;

Schedule::command('donations:process-recurring')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('currency:fetch-rates')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('media:optimize --recent=120')
    ->everyTwoHours()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer();
