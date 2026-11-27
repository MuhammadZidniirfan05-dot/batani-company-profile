<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteSettingService
{
    /**
     * Mengambil seluruh pengaturan publik.
     */
    public function publicSettings(): array
    {
        return collect($this->cachedSettings())
            ->mapWithKeys(
                fn (array $setting): array => [
                    $setting['key'] =>
                        $this->formatValue($setting),
                ]
            )
            ->all();
    }

    /**
     * Mengambil pengaturan publik berdasarkan kelompok.
     */
    public function settingsByGroup(
        string $group
    ): array {
        return collect($this->cachedSettings())
            ->where('group', $group)
            ->mapWithKeys(
                fn (array $setting): array => [
                    $setting['key'] =>
                        $this->formatValue($setting),
                ]
            )
            ->all();
    }

    /**
     * Mengambil satu nilai pengaturan publik.
     */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {
        $settings = $this->publicSettings();

        return $settings[$key] ?? $default;
    }

    /**
     * Mengambil data melalui satu sumber cache.
     */
    private function cachedSettings(): array
    {
        return Cache::remember(
            SiteSetting::PUBLIC_CACHE_KEY,
            now()->addHours(12),
            function (): array {

                return SiteSetting::query()
                    ->where('is_public', true)
                    ->orderBy('group')
                    ->orderBy('sort_order')
                    ->get([
                        'group',
                        'key',
                        'value',
                        'type',
                    ])
                    ->toArray();

            }
        );
    }

    /**
     * Mengubah nilai sesuai tipe pengaturan.
     */
    private function formatValue(
        array $setting
    ): ?string {
        $value = $setting['value'] ?? null;

        if (blank($value)) {
            return null;
        }

        if ($setting['type'] === 'image') {

            // Mendukung URL gambar eksternal yang valid.
            if (
                Str::startsWith(
                    $value,
                    ['http://', 'https://']
                )
            ) {
                return filter_var(
                    $value,
                    FILTER_VALIDATE_URL
                ) ? $value : null;
            }

            // Mengubah path gambar lokal menjadi URL.
            return Storage::disk('public')->url($value);
        }

        return $value;
    }

    /**
     * Membersihkan cache pengaturan.
     */
    public function clearCache(): void
    {
        Cache::forget(
            SiteSetting::PUBLIC_CACHE_KEY
        );

        // Membersihkan sisa cache konfigurasi lama.
        foreach ([
            'general',
            'contact',
            'branding',
            'seo',
        ] as $group) {

            Cache::forget(
                'site_settings.group.' . $group
            );

        }
    }
}
