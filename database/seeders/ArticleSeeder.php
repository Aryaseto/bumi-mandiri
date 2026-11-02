<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->value('id');

        $articles = [
            ['Panduan Lengkap Tubing untuk Pemula di Bumi Mandiri', 'Perlengkapan, ritme arus, dan hal yang perlu diperhatikan sebelum tubing pertama.'],
            ['5 Alasan Camping di Bumi Mandiri Sukabumi Wajib Dicoba', 'Udara pagi, tenda sederhana, dan malam tanpa layar: alasan berkemah masih relevan.'],
            ['Tips Mempersiapkan Outbound Perusahaan yang Berkesan', 'Menyusun acara outbound yang terarah, dari tujuan sampai pembagian waktu.'],
            ['Serunya Archery: Olahraga Fokus di Tengah Alam', 'Panahan melatih fokus dan kesabaran. Ini gambaran sesi pertamanya.'],
            ['Mengenal Lebih Dekat Sungai Cipelang, Surga Tubing Sukabumi', 'Mengenal aliran sungai di sekitar Sukabumi dan aktivitas air yang biasa dilakukan.'],
            ['Ide Kegiatan LDK yang Membentuk Jiwa Kepemimpinan', 'Latihan kepemimpinan yang berangkat dari masalah nyata, bukan sekadar permainan.'],
            ['Kenapa Api Unggun Selalu Jadi Momen Favorit Wisatawan', 'Api unggun menutup hari dengan percakapan dan cerita. Ini yang membuatnya bertahan.'],
            ['Panduan Memilih Paket Wisata Sesuai Kebutuhan Rombongan', 'Dari jumlah peserta sampai durasi, ini pertimbangan memilih paket kunjungan.'],
            ['Cerita di Balik Berdirinya Bumi Mandiri Sukabumi', 'Catatan tentang ruang terbuka yang tumbuh dari kebiasaan berkumpul di alam.'],
            ['Persiapan Fisik Sebelum Ikut Paintball Battle', 'Kesiapan fisik dan aturan keselamatan sebelum bermain paintball di area terbuka.'],
            ['Destinasi Foto Instagramable di Bumi Mandiri', 'Sudut yang sering jadi latar foto dan waktu terbaik untuk memotretnya.'],
            ['Manfaat Team Building bagi Produktivitas Karyawan', 'Team building yang terarah membantu komunikasi dan pembagian peran di tempat kerja.'],
        ];

        foreach ($articles as $index => [$title, $excerpt]) {
            Article::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'author_id' => $authorId,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => "[DATA PERLU DIKONFIRMASI] Konten lengkap artikel \"{$title}\" akan diisi oleh tim editorial Bumi Mandiri.",
                    'reading_time' => 3 + ($index % 6),
                    'is_published' => true,
                ]
            );
        }
    }
}
