<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Registrasi application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Mengatur zona waktu dashboard BATANI.
     */
    public function boot(): void
    {
        FilamentTimezone::set('Asia/Jakarta');
    }
}
