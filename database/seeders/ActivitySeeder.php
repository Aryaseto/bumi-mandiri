<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $catId = fn (string $name) => Category::where('name', $name)->value('id');
        $destIds = Destination::pluck('id')->all();
        $randomDest = fn () => $destIds[array_rand($destIds)];

        $activities = [
            ['name' => 'Tubing Experience', 'category' => 'Adventure', 'price' => 75000, 'duration' => 120, 'capacity' => 20, 'desc' => 'Susuri Sungai Cipelang dengan ban tubing, memacu adrenalin di antara bebatuan dan arus jernih.'],
            ['name' => 'Paintball Battle', 'category' => 'Adventure', 'price' => 100000, 'duration' => 90, 'capacity' => 24, 'desc' => 'Pertempuran strategi seru menggunakan paintball di arena hutan buatan.'],
            ['name' => 'Camping Ground Stay', 'category' => 'Nature', 'price' => 150000, 'duration' => 720, 'capacity' => 50, 'desc' => 'Bermalam di tengah alam dengan tenda dan suasana hutan pinus yang menenangkan.'],
            ['name' => 'Api Unggun Malam', 'category' => 'Nature', 'price' => 50000, 'duration' => 90, 'capacity' => 40, 'desc' => 'Kebersamaan hangat mengelilingi api unggun dengan musik dan cerita malam.'],
            ['name' => 'Outbound Team Adventure', 'category' => 'Team Building', 'price' => 120000, 'duration' => 180, 'capacity' => 40, 'desc' => 'Rangkaian permainan outbound untuk membangun kekompakan dan komunikasi tim.'],
            ['name' => 'Ice Breaking Session', 'category' => 'Team Building', 'price' => 60000, 'duration' => 60, 'capacity' => 50, 'desc' => 'Sesi pemanasan interaktif untuk mencairkan suasana sebelum kegiatan utama.'],
            ['name' => 'Archery Challenge', 'category' => 'Skill', 'price' => 80000, 'duration' => 90, 'capacity' => 15, 'desc' => 'Uji fokus dan ketepatan dengan panahan tradisional dipandu instruktur berpengalaman.'],
            ['name' => 'LDK Leadership Camp', 'category' => 'Education', 'price' => 200000, 'duration' => 480, 'capacity' => 60, 'desc' => 'Program Latihan Dasar Kepemimpinan dengan simulasi tantangan lapangan.'],
            ['name' => 'Pramuka Adventure Camp', 'category' => 'Education', 'price' => 100000, 'duration' => 480, 'capacity' => 60, 'desc' => 'Kegiatan kepramukaan dengan permainan alam dan pos-pos tantangan.'],
            ['name' => 'Team Building Challenge', 'category' => 'Team Building', 'price' => 130000, 'duration' => 180, 'capacity' => 45, 'desc' => 'Tantangan kolaboratif berskala besar untuk korporasi dan organisasi.'],
            ['name' => 'Meeting & Gathering Package', 'category' => 'Team Building', 'price' => 90000, 'duration' => 240, 'capacity' => 100, 'desc' => 'Ruang terbuka dan fasilitas pendukung untuk meeting sambil menikmati alam.'],
            ['name' => 'Yearbook Photo Session', 'category' => 'Nature', 'price' => 70000, 'duration' => 120, 'capacity' => 30, 'desc' => 'Lokasi foto ikonik untuk kenangan yearbook sekolah atau komunitas.'],
        ];

        foreach ($activities as $a) {
            Activity::firstOrCreate(
                ['slug' => Str::slug($a['name'])],
                [
                    'category_id' => $catId($a['category']),
                    'destination_id' => $randomDest(),
                    'name' => $a['name'],
                    'short_description' => $a['desc'],
                    'description' => $a['desc'],
                    'price' => $a['price'],
                    'duration_minutes' => $a['duration'],
                    'capacity' => $a['capacity'],
                    'gallery' => [],
                    'is_featured' => rand(0, 1),
                    'is_active' => true,
                ]
            );
        }
    }
}
