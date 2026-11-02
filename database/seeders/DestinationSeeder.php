<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Bumi Mandiri Camp Ground',
                'description' => 'Area perkemahan utama dikelilingi hutan pinus dan udara sejuk pegunungan Sukabumi.',
                'location' => 'Sukabumi, Jawa Barat',
            ],
            [
                'name' => 'Sungai Cipelang',
                'description' => 'Aliran sungai jernih yang menjadi lokasi utama aktivitas tubing.',
                'location' => 'Sukabumi, Jawa Barat',
            ],
            [
                'name' => 'Lapangan Outbound Bumi Mandiri',
                'description' => 'Area terbuka luas untuk aktivitas team building dan outbound.',
                'location' => 'Sukabumi, Jawa Barat',
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::firstOrCreate(
                ['slug' => Str::slug($dest['name'])],
                [
                    'name' => $dest['name'],
                    'description' => $dest['description'],
                    'location' => $dest['location'],
                    'google_maps_url' => '[DATA PERLU DIKONFIRMASI]',
                ]
            );
        }
    }
}
