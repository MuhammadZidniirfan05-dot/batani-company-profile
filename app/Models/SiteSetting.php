<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    public const PUBLIC_CACHE_KEY = 'site_settings.public';

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'is_public',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Menghapus cache ketika pengaturan disimpan.
     */
    protected static function booted(): void
    {
        static::saved(function (SiteSetting $setting): void {
            self::clearSettingsCache();
        });

        static::deleted(function (SiteSetting $setting): void {
            self::clearSettingsCache();
        });
    }

    /**
     * Membersihkan seluruh cache Site Settings.
     */
    protected static function clearSettingsCache(): void
    {
        Cache::forget(self::PUBLIC_CACHE_KEY);

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
