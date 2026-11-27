<?php

namespace App\Http\Middleware;

use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Template utama aplikasi Inertia.
     */
    protected $rootView = 'app';

    /**
     * Versi aset frontend.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Data global yang dibagikan ke seluruh halaman Inertia.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'siteSettings' => fn (): array =>
                app(SiteSettingService::class)->publicSettings(),

        ];
    }
}
