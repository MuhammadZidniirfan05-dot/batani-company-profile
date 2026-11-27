<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            // INFORMASI UMUM

            [
                'group' => 'general',
                'key' => 'company_name',
                'value' => 'BATANI',
                'type' => 'text',
                'is_public' => true,
                'sort_order' => 1,
            ],

            [
                'group' => 'general',
                'key' => 'company_tagline',
                'value' => null,
                'type' => 'text',
                'is_public' => true,
                'sort_order' => 2,
            ],

            [
                'group' => 'general',
                'key' => 'company_description',
                'value' => null,
                'type' => 'textarea',
                'is_public' => true,
                'sort_order' => 3,
            ],

            // IDENTITAS VISUAL

            [
                'group' => 'branding',
                'key' => 'company_logo',
                'value' => null,
                'type' => 'image',
                'is_public' => true,
                'sort_order' => 4,
            ],

            [
                'group' => 'branding',
                'key' => 'company_favicon',
                'value' => null,
                'type' => 'image',
                'is_public' => true,
                'sort_order' => 5,
            ],

            // INFORMASI KONTAK

            [
                'group' => 'contact',
                'key' => 'company_email',
                'value' => null,
                'type' => 'email',
                'is_public' => true,
                'sort_order' => 6,
            ],

            [
                'group' => 'contact',
                'key' => 'company_phone',
                'value' => null,
                'type' => 'phone',
                'is_public' => true,
                'sort_order' => 7,
            ],

            [
                'group' => 'contact',
                'key' => 'company_address',
                'value' => null,
                'type' => 'textarea',
                'is_public' => true,
                'sort_order' => 8,
            ],

            // SEO WEBSITE

            [
                'group' => 'seo',
                'key' => 'default_seo_title',
                'value' => 'BATANI',
                'type' => 'text',
                'is_public' => true,
                'sort_order' => 9,
            ],

            [
                'group' => 'seo',
                'key' => 'default_seo_description',
                'value' => null,
                'type' => 'textarea',
                'is_public' => true,
                'sort_order' => 10,
            ],

        ];

        foreach ($settings as $setting) {

            SiteSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );

        }

        app(SiteSettingService::class)
            ->clearCache();
    }
}
