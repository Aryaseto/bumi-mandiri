<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        // Enam foto contoh yang tersedia di public/images/gallery.
        // Ganti dengan foto asli Bumi Mandiri sebelum publikasi.
        $items = [
            ['title' => 'Air Terjun di Sekitar Lokasi', 'category' => 'nature', 'image' => 'gallery/placeholder-1.jpg'],
            ['title' => 'Jalur Edukasi Alam', 'category' => 'education', 'image' => 'gallery/placeholder-2.jpg'],
            ['title' => 'Keseruan Menyusuri Sungai', 'category' => 'adventure', 'image' => 'gallery/placeholder-4.jpg'],
            ['title' => 'Tenda di Area Camp Ground', 'category' => 'nature', 'image' => 'gallery/placeholder-3.jpg'],
            ['title' => 'Latihan Panahan', 'category' => 'skill', 'image' => 'gallery/placeholder-5.jpg'],
            ['title' => 'Kebersamaan di Api Unggun', 'category' => 'team-building', 'image' => 'gallery/placeholder-6.jpg'],
        ];

        foreach ($items as $item) {
            Gallery::updateOrCreate(
                ['title' => $item['title']],
                [
                    'category_id' => $categories[$item['category']] ?? null,
                    'image_path' => $item['image'],
                    'is_featured' => true,
                ]
            );
        }
    }
}
