<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteSettingService
{
    /**
     * Mengambil seluruh pengaturan publik website.
     *
     * Hasil disimpan dalam cache agar query database
     * tidak dijalankan berulang kali pada setiap request.
     */
    public function publicSettings(): array
    {
        return Cache::remember(
            SiteSetting::PUBLIC_CACHE_KEY,
            now()->addHours(12),
            function (): array {

                $settings = SiteSetting::query()
                    ->where('is_public', true)
                    ->orderBy('group')
                    ->orderBy('sort_order')
                    ->get([
                        'key',
                        'value',
                        'type',
                    ]);

                return $settings
                    ->mapWithKeys(function (SiteSetting $setting): array {

                        $value = $setting->value;

                        // Mengubah path gambar menjadi URL publik.
                        if (
                            $setting->type === 'image' &&
                            filled($value)
                        ) {
                            $value = Str::startsWith(
                                $value,
                                ['http://', 'https://']
                            )
                                ? $value
                                : Storage::disk('public')->url($value);
                        }

                        return [
                            $setting->key => $value,
                        ];

                    })
                    ->all();
            }
        );
    }

    /**
     * Mengambil pengaturan berdasarkan kelompok.
     *
     * Contoh:
     * general, contact, branding, seo.
     */
    public function settingsByGroup(string $group): array
    {
        return Cache::remember(
            'site_settings.group.' . $group,
            now()->addHours(12),
            function () use ($group): array {

                return SiteSetting::query()
                    ->where('group', $group)
                    ->where('is_public', true)
                    ->orderBy('sort_order')
                    ->pluck('value', 'key')
                    ->all();

            }
        );
    }

    /**
     * Menghapus cache pengaturan website.
     */
    public function clearCache(): void
    {
        Cache::forget(SiteSetting::PUBLIC_CACHE_KEY);

        foreach ([
            'general',
            'contact',
            'branding',
            'seo',
        ] as $group) {
            Cache::forget('site_settings.group.' . $group);
        }
    }
}
