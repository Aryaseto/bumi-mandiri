<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Adventure', 'icon' => 'mountain', 'description' => 'Aktivitas penuh adrenalin di alam terbuka.'],
            ['name' => 'Nature', 'icon' => 'tree', 'description' => 'Menikmati kedamaian alam Bumi Mandiri.'],
            ['name' => 'Team Building', 'icon' => 'users', 'description' => 'Aktivitas kolaboratif untuk kelompok.'],
            ['name' => 'Skill', 'icon' => 'target', 'description' => 'Aktivitas melatih fokus dan ketangkasan.'],
            ['name' => 'Education', 'icon' => 'book', 'description' => 'Kegiatan edukatif dan kepemimpinan.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
