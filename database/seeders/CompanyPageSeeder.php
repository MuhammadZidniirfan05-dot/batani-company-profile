<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CompanyPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [

            // TENTANG KAMI

            [
                'page_key' => 'about',
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'excerpt' => null,
                'content' => null,
                'featured_image' => null,
                'status' => 'draft',
                'published_at' => null,
                'seo_title' => 'Tentang Kami',
                'seo_description' => null,
            ],

            // VISI DAN MISI

            [
                'page_key' => 'vision-mission',
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'excerpt' => null,
                'content' => null,
                'featured_image' => null,
                'status' => 'draft',
                'published_at' => null,
                'seo_title' => 'Visi dan Misi',
                'seo_description' => null,
            ],

            // TUJUAN PERUSAHAAN

            [
                'page_key' => 'company-goals',
                'title' => 'Tujuan Perusahaan',
                'slug' => 'tujuan-perusahaan',
                'excerpt' => null,
                'content' => null,
                'featured_image' => null,
                'status' => 'draft',
                'published_at' => null,
                'seo_title' => 'Tujuan Perusahaan',
                'seo_description' => null,
            ],

        ];

        foreach ($pages as $page) {

            Page::firstOrCreate(
                ['page_key' => $page['page_key']],
                $page
            );

        }
    }
}
