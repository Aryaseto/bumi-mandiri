<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Weekend Escape',
                'target_segment' => 'keluarga, pasangan, teman',
                'description' => 'Liburan akhir pekan santai dengan aktivitas ringan dan suasana alam yang menenangkan.',
                'base_price' => 250000,
                'min_participants' => 2,
                'max_participants' => 10,
                'estimated_duration' => '1 Hari',
                'activities' => ['Tubing Experience', 'Camping Ground Stay', 'Api Unggun Malam'],
            ],
            [
                'name' => 'Team Adventure',
                'target_segment' => 'perusahaan, organisasi, komunitas',
                'description' => 'Paket lengkap untuk membangun kekompakan tim melalui rangkaian aktivitas outbound.',
                'base_price' => 400000,
                'min_participants' => 10,
                'max_participants' => 100,
                'estimated_duration' => '1-2 Hari',
                'activities' => ['Outbound Team Adventure', 'Ice Breaking Session', 'Team Building Challenge'],
            ],
            [
                'name' => 'School Adventure',
                'target_segment' => 'sekolah, mahasiswa, pramuka',
                'description' => 'Program edukatif dan kepemimpinan yang dirancang untuk pelajar dan mahasiswa.',
                'base_price' => 300000,
                'min_participants' => 15,
                'max_participants' => 150,
                'estimated_duration' => '1-2 Hari',
                'activities' => ['LDK Leadership Camp', 'Pramuka Adventure Camp', 'Archery Challenge'],
            ],
            [
                'name' => 'Custom Event',
                'target_segment' => 'event, gathering, yearbook, prewedding',
                'description' => 'Paket fleksibel yang dapat disesuaikan untuk kebutuhan acara khusus Anda.',
                'base_price' => 350000,
                'min_participants' => 5,
                'max_participants' => null,
                'estimated_duration' => 'Fleksibel',
                'activities' => ['Yearbook Photo Session', 'Meeting & Gathering Package'],
            ],
        ];

        foreach ($packages as $p) {
            $package = Package::firstOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'name' => $p['name'],
                    'target_segment' => $p['target_segment'],
                    'description' => $p['description'],
                    'base_price' => $p['base_price'],
                    'min_participants' => $p['min_participants'],
                    'max_participants' => $p['max_participants'],
                    'estimated_duration' => $p['estimated_duration'],
                    'is_active' => true,
                ]
            );

            $activityIds = Activity::whereIn('name', $p['activities'])->pluck('id');
            $package->activities()->sync($activityIds);
        }
    }
}
