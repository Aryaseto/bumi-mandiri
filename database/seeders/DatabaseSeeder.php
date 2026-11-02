<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            DestinationSeeder::class,
            ActivitySeeder::class,
            PackageSeeder::class,
            ArticleSeeder::class,
            GallerySeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
