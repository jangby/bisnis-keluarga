<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\AutoAlphaCheck;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('stock:check-reminder')->dailyAt('08:00');
Schedule::command('app:auto-alpha')->dailyAt('00:01')->timezone('Asia/Jakarta');
