<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Jadwalkan ping otomatis ke Supabase setiap hari jam 01:00 UTC (08:00 WIB)
Schedule::command('supabase:ping')->dailyAt('01:00');

