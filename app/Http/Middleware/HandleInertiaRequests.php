<?php

namespace App\Http\Middleware;

use App\Models\SocialLink;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Template utama Inertia.
     */
    protected $rootView = 'app';

    /**
     * Menentukan versi aset frontend.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Data global untuk seluruh halaman publik.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            // PENGATURAN WEBSITE

            'siteSettings' => fn (): array =>
                app(SiteSettingService::class)
                    ->publicSettings(),

            // MEDIA SOSIAL WEBSITE

            'siteSocialLinks' => fn (): array =>
                SocialLink::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('platform')
                    ->get([
                        'id',
                        'platform',
                        'label',
                        'url',
                        'icon',
                    ])
                    ->filter(function (SocialLink $link): bool {

                        $scheme = parse_url(
                            $link->url,
                            PHP_URL_SCHEME
                        );

                        return filter_var(
                            $link->url,
                            FILTER_VALIDATE_URL
                        ) !== false
                            && in_array(
                                strtolower((string) $scheme),
                                ['http', 'https'],
                                true
                            );
                    })
                    ->values()
                    ->toArray(),

        ];
    }
}
