<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler (Laravel 12 style): ejecutar recordatorios de carritos abandonados
Schedule::command('recordatorio:carrito')->everyTwoHours();
