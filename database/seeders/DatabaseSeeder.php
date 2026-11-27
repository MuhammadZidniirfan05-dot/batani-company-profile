<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Menyiapkan data awal website BATANI.
     */
    public function run(): void
    {
        $this->call([

            SiteSettingSeeder::class,

            CompanyPageSeeder::class,

        ]);
    }
}
