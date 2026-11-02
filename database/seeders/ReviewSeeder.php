<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = [
            ['tubing-experience', 'Rina Kartika', 5, 'Arusnya seru tapi tetap aman. Pemandu sabar dan banyak membantu anak-anak. Pasti kembali lagi.', 5, true],
            ['tubing-experience', 'Bagas Prakoso', 5, 'Rute sungainya panjang dan pemandangannya bagus. Peralatan lengkap, tinggal bawa baju ganti.', 12, true],
            ['tubing-experience', 'Nadia Putri', 4, 'Seru untuk rombongan kantor. Kalau datang siang, sebaiknya bawa pelindung matahari.', 21, true],
            ['camping-ground-stay', 'Fajar Nugroho', 5, 'Area tenda bersih, toilet terawat, dan suara sungai bikin tidur nyenyak. Recommended.', 8, true],
            ['camping-ground-stay', 'Ayu Lestari', 5, 'Malam pertama camping langsung jatuh cinta. Api unggun dan langitnya luar biasa.', 16, true],
            ['paintball-battle', 'Hendra Wijaya', 5, 'Arena luas dan strateginya seru. Instruktur tegas soal keselamatan, jadi tenang mainnya.', 4, true],
            ['paintball-battle', 'Maya Sari', 4, 'Seru banget buat team building. Semoga ke depan ada paket minuman tambahan.', 19, true],
            ['archery-challenge', 'Dimas Saputra', 5, 'Peralatan panahan bagus dan pendampingannya jelas. Anak-anak cepat bisa.', 11, true],
            ['archery-challenge', 'Lilis Handayani', 4, 'Aktivitas yang cocok untuk pemula. Lokasinya sejuk dan tidak terlalu ramai.', 27, true],
            ['outbound-team-adventure', 'Andi Firmansyah', 5, 'Tim kami jadi lebih kompak setelah outbound di sini. Fasilitatornya energik.', 3, true],
            ['api-unggun-malam', 'Sari Melati', 5, 'Suasana api unggunnya hangat dan intimate. Cocok untuk gathering keluarga besar.', 14, true],
            ['ice-breaking-session', 'Yusuf Maulana', 4, 'Games-nya variatif dan tidak membosankan. Peserta dari divisi berbeda jadi cepat akrab.', 9, true],
            ['yearbook-photo-session', 'Rani Puspita', 5, 'Spot fotonya banyak dan hasilnya bagus. Tim dokumentasi sangat kooperatif.', 6, true],
            ['meeting-gathering-package', 'Taufik Hidayat', 4, 'Ruang meeting nyaman dan koneksi cukup stabil. Makan siangnya enak.', 23, true],
            ['camping-ground-stay', 'Bimo Aditya', 3, 'Tempatnya bagus, hanya saja saat kami datang area parkir agak penuh.', 2, false],
            ['tubing-experience', 'Citra Amelia', 5, 'Pengalaman pertama tubing dan langsung suka. Pemandu sangat perhatian.', 1, false],
        ];

        $service = app(ReviewService::class);
        $activities = Activity::all()->keyBy('slug');

        foreach ($ratings as [$slug, $name, $rating, $comment, $daysAgo, $approved]) {
            $activity = $activities->get($slug);

            if (! $activity) {
                continue;
            }

            $exists = Review::where('activity_id', $activity->id)
                ->where('guest_name', $name)
                ->exists();

            if ($exists) {
                continue;
            }

            $review = Review::create([
                'user_id' => null,
                'booking_id' => null,
                'activity_id' => $activity->id,
                'guest_name' => $name,
                'guest_email' => null,
                'rating' => $rating,
                'comment' => $comment,
                'ip_address' => null,
                'is_approved' => $approved,
            ]);

            $review->timestamps = false;
            $review->forceFill([
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ])->save();
        }

        $activities->each(fn (Activity $activity) => $service->syncActivityRating($activity->id));
    }
}
