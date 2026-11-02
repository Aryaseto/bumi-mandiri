<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Bumi Mandiri',
            'tagline' => 'Your Next Adventure Starts Here',
            'phone' => '[DATA PERLU DIKONFIRMASI]',
            'email' => 'hello@bumimandiri.id',
            'whatsapp' => '[DATA PERLU DIKONFIRMASI]',
            'address' => 'Sukabumi, Jawa Barat',
            'instagram' => '[DATA PERLU DIKONFIRMASI]',
            'google_maps_url' => '[DATA PERLU DIKONFIRMASI]',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
