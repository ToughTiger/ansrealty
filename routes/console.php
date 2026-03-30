<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automated tasks
Schedule::command('leads:mark-stale')->daily()->at('09:00');
Schedule::command('opportunities:auto-close')->weekly()->mondays()->at('10:00');
