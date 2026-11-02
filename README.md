# BUMI MANDIRI — Premium Outdoor Experience & Destination Booking Platform

Website booking destinasi wisata dan aktivitas outdoor berbasis **Laravel 11 + MySQL**, dibangun untuk project akademik:
**"Perancangan Website Booking Destinasi Wisata dan Aktivitas Outdoor Berbasis Laravel dan Database MySQL"**

---

## ⚠️ Catatan Penting Sebelum Setup

> **Update:** di macOS cukup ikuti [`README-MAC.md`](README-MAC.md) (`bash setup-mac.sh`). Skeleton Laravel pada folder ini sudah lengkap, jadi langkah pembuatan skeleton di bawah **tidak diperlukan lagi**.


Source code ini ditulis secara manual (bukan hasil `composer create-project`), karena environment
yang membuatnya tidak memiliki akses ke Packagist. **Cara paling aman menjalankannya:**

```bash
# 1. Buat skeleton Laravel 11 baru (kosong) di lokasi terpisah
composer create-project laravel/laravel laravel-skeleton

# 2. Salin folder skeleton bawaan (yang TIDAK kami tulis ulang) ke project ini:
#    - public/ (kecuali storage symlink)
#    - config/
#    - artisan
#    - vendor/ (akan dibuat otomatis oleh composer install)

# 3. Salin folder project ini (bumi-mandiri/) menimpa app/, database/, resources/, routes/,
#    bootstrap/app.php, composer.json, package.json, vite.config.js, postcss.config.js,
#    tailwind.config.js ke dalam skeleton tersebut.
```

Alternatif lebih cepat: buat project baru dengan `laravel new bumi-mandiri`, lalu **timpa** folder
`app/`, `database/`, `resources/`, `routes/`, dan file `bootstrap/app.php` dengan isi dari project ini.

---

## 🚀 Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate

# Sesuaikan kredensial database di .env, lalu:
php artisan migrate --seed

# Buat symlink storage agar gambar upload bisa diakses publik
php artisan storage:link

npm install
npm run dev      # untuk development
# atau
npm run build     # untuk production

php artisan serve
```

Buka `http://localhost:8000`

---

## 🔑 Akun Default (dari Seeder)

| Role  | Email                  | Password   |
|-------|-------------------------|------------|
| Admin | admin@bumimandiri.id    | password   |
| User  | dewi@example.com        | password   |
| User  | rizky@example.com       | password   |

Admin panel: `http://localhost:8000/admin`

---

## 🧪 Testing

```bash
php artisan test
```

Mencakup: proteksi route booking untuk guest, alur booking end-to-end (kalkulasi harga +
generate booking code), proteksi route admin dari user biasa, serta rating pengunjung
tanpa login (validasi, honeypot, dan sinkronisasi rata-rata rating).

---

## 📁 Struktur Utama

```
app/
├── Http/Controllers/       Public, Auth, dan Admin/ controllers
├── Http/Requests/          Form Request validation
├── Http/Middleware/        EnsureUserIsAdmin
├── Models/                 15 Eloquent model dengan relasi lengkap
├── Services/                BookingService, NotificationService
├── Notifications/          BookingStatusChanged
database/
├── migrations/             11 file, 15 tabel
├── seeders/                 12 seeder (data demo lengkap)
resources/views/
├── layouts/                 app, admin, error
├── components/               navbar, footer, activity-card, admin/*
├── home, activities, destinations, packages, gallery, articles,
├── booking, dashboard, admin, auth, pages, search, errors
routes/web.php                Seluruh route publik, user, admin
```

---

## ✅ Final Acceptance Criteria

- [x] Migration & seeder lengkap (15 tabel, data demo hidup)
- [x] Register / Login / Logout
- [x] User dashboard (upcoming, history, favorite, review, profile)
- [x] Admin dashboard (KPI, chart, recent booking table)
- [x] CRUD admin untuk 9 modul (Users, Categories, Destinations, Activities, Packages,
      Bookings-status, Reviews, Articles, Gallery, Settings)
- [x] Activity detail + search + filter (kategori, rating, durasi) + sort
- [x] Favorite / wishlist
- [x] Multi-step booking dengan real-time price calculation (Alpine.js)
- [x] Booking checkout → payment record (manual) → status lifecycle
- [x] Review hanya untuk booking berstatus Completed, dengan approval admin
- [x] Rating pengunjung umum tanpa login (nama + bintang + ulasan opsional),
      tampil di halaman aktivitas dan beranda, dengan moderasi admin
- [x] Article (travel editorial) & Gallery (masonry + lightbox sederhana)
- [x] Contact form
- [x] Responsive (mobile-first, hamburger nav, sticky elements)
- [x] Custom error page: 404 / 403 / 419 / 422 / 500
- [x] Security: CSRF (Blade `@csrf` bawaan), middleware `admin`, Form Request validation,
      password hashing (`Hash::make`), mass-assignment protection (`$fillable`),
      file upload validation (mimes+size)
- [x] Performance: eager loading (`with()`) di semua controller list, pagination,
      index database pada kolom yang sering di-filter (`is_active`, `is_featured`, `status`)
- [x] Database relationship sesuai ERD (booking_items untuk skalabilitas)
- [x] Tidak ada broken route — semua 40+ route publik/user/admin punya view

## ⭐ Rating Pengunjung

Rating dapat dikirim tanpa login dan tanpa booking melalui form di halaman detail aktivitas
(`/activities/{slug}#rating`). Data yang disimpan: nama, email (opsional), bintang 1–5,
ulasan (opsional), dan alamat IP untuk pencegahan spam.

- Rating pengunjung umum **langsung tampil**; admin dapat menyembunyikan atau menghapusnya
  lewat **Admin → Review**.
- Rata-rata dan jumlah rating aktivitas dihitung ulang otomatis dari rating yang disetujui
  (`App\Services\ReviewService`).
- Review dari booking yang sudah selesai tetap berstatus menunggu persetujuan admin dan
  ditandai **Terverifikasi**.
- Untuk mewajibkan moderasi sebelum tampil, ubah `'is_approved' => true` menjadi `false`
  pada `ReviewController::storeVisitor()`.

Data demo rating: `php artisan db:seed --class=ReviewSeeder`.

---

## 📝 Data Placeholder

Beberapa data kontak (`phone`, `whatsapp`, `google_maps_url`, `instagram`) dan konten artikel
ditandai `[DATA PERLU DIKONFIRMASI]` sesuai instruksi — silakan lengkapi lewat **Admin → Settings**
atau langsung di seeder sebelum deploy produksi.
# Bumi-Mandiri
