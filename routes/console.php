<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Jadwalkan ping otomatis ke Supabase setiap hari jam 01:00 UTC (08:00 WIB)
Schedule::command('supabase:ping')->dailyAt('01:00');

// Jadwalkan sinkronisasi artikel otomatis dari Diskominfo Jatim setiap hari jam 06:00 WIB (memindai 5 halaman)
Schedule::command('artikel:sync-jatimprov --limit=25 --pages=5')->dailyAt('06:00');

