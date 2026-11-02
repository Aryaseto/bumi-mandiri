# Bumi Mandiri — Menjalankan di macOS

Versi project ini sudah dirapikan agar langsung bisa dijalankan di Mac:
tanpa `node_modules` / `vendor` bawaan Windows, tanpa `.env` lama, dan dengan skrip setup otomatis.

- **Stack:** Laravel 11 · PHP 8.2+ · MySQL · Blade · Tailwind (Vite) · Alpine.js
- **Aset CSS/JS sudah ter-build** (`public/build`), jadi **Node.js tidak wajib** hanya untuk menjalankan website.

## Cara tercepat (3 langkah)

1. **Pasang Homebrew** (jika belum): <https://brew.sh>
2. **Ekstrak zip**, lalu buka Terminal di folder `bumi-mandiri`:
   ```bash
   cd ~/Downloads/bumi-mandiri        # sesuaikan lokasinya
   bash setup-mac.sh
   ```
   Skrip akan memasang PHP / Composer / MySQL bila belum ada, lalu otomatis:
   `composer install` → membuat `.env` + `APP_KEY` → menyalakan MySQL → membuat database `bumi_mandiri`
   → `migrate` + seed data awal → `storage:link`.
3. **Jalankan website:**
   ```bash
   bash start-mac.sh
   ```
   Buka <http://localhost:8000> (panel admin: <http://localhost:8000/admin>).

> Pakai `bash setup-mac.sh` (bukan `./setup-mac.sh`) supaya tidak perlu `chmod +x` dan tidak diblokir Gatekeeper.

| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | admin@bumimandiri.id | password   |
| User  | dewi@example.com     | password   |
| User  | rizky@example.com    | password   |

Ganti password default sebelum website dipakai di luar komputer Anda.

## Opsi skrip

| Perintah | Fungsi |
|----------|--------|
| `bash setup-mac.sh` | Setup / perbarui. Aman diulang; data yang ada tidak ditimpa. |
| `bash setup-mac.sh --fresh` | Hapus **semua tabel**, migrate + seed ulang (minta konfirmasi `ya`). |
| `bash setup-mac.sh --rebuild` | Build ulang CSS/JS (memasang Node.js bila belum ada). |
| `bash start-mac.sh` | Nyalakan MySQL (jika perlu) dan jalankan server. |
| `PORT=8001 bash start-mac.sh` | Jalankan di port lain. |

## Struktur project

```
bumi-mandiri/
├── setup-mac.sh · start-mac.sh · README-MAC.md   ← tambahan untuk Mac
├── .env.example            ← template konfigurasi (.env dibuat otomatis oleh setup)
├── app/
│   ├── Http/Controllers/Admin/   ← CRUD admin (destinasi, aktivitas, paket, booking, dll.)
│   ├── Http/Requests/            ← validasi form
│   └── Models/
├── database/ migrations · seeders · factories
├── resources/views/        ← Blade (layouts, admin/, home/, components/)
├── routes/web.php
├── public/                 ← document root (build/ = CSS/JS hasil build)
├── storage/                ← upload, log, cache (kosong saat dikirim)
└── tests/Feature/          ← test otomatis
```

## Langkah manual (jika tidak memakai skrip)

```bash
brew install php composer mysql            # + node bila ingin mengubah tampilan
brew services start mysql
mysql -u root -e "CREATE DATABASE bumi_mandiri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

composer install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Mengubah tampilan (class Tailwind di `resources/views`)? Jalankan `npm install`, lalu `npm run dev`
di terminal terpisah (atau `npm run build` sekali).

## Menjalankan test

```bash
php artisan test
```

`phpunit.xml` sudah diarahkan ke **SQLite in-memory**, jadi test tidak menyentuh database MySQL Anda.
Pastikan ekstensi `pdo_sqlite` aktif (`php -m | grep sqlite`) — bawaan PHP dari Homebrew.

## Pemecahan masalah

| Gejala | Solusi |
|--------|--------|
| `Access denied for user 'root'` | Isi `DB_PASSWORD` di `.env` sesuai password MySQL Anda, jalankan ulang `bash setup-mac.sh`. |
| Tidak bisa konek ke MySQL | `brew services list` → pastikan `mysql` berstatus *started*. Pakai MAMP? Set `DB_PORT=8889` dan `DB_PASSWORD=root`. Pakai DBngin/Herd? Samakan port & password di `.env`. |
| `Table 'users' already exists` | Sisa migrasi lama di database. `bash setup-mac.sh --fresh` (menghapus semua tabel). |
| `Vite manifest not found` | `bash setup-mac.sh --rebuild` |
| Gambar upload tidak tampil | `php artisan storage:link --force` |
| `Address already in use` | Port 8000 terpakai: `PORT=8001 bash start-mac.sh` |
| `Class ... not found` / folder `vendor` hilang | `composer install` |
| `Please provide a valid cache path` | Pastikan folder `storage/framework/views` ada, lalu `php artisan view:clear`. |
| Banner di beranda kosong | Letakkan file `hero-forest.jpg` di `public/images/` (file ini dirujuk CSS tetapi tidak ikut ada di zip asli). |

## Perubahan dibanding zip asli

- Dibuang: `node_modules` (biner Windows), `vendor`, `.env` lama, `storage/logs`, cache & view terkompilasi Windows, `bootstrap/cache/*.php`.
- `.env.example`: `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` (migrasi tidak membuat tabel `cache`/`jobs`).
- `phpunit.xml`: test memakai SQLite in-memory agar database development aman.
- `public/build`: dibangun ulang (termasuk class Tailwind untuk view admin yang diperbaiki).
- Perbaikan CRUD Destinasi & Aktivitas (slug unik, cegah hapus data berelasi, hapus gambar lama, validasi, filter) + test `tests/Feature/Admin/AdminCrudTest.php`.
- Ditambah: `setup-mac.sh`, `start-mac.sh`, `README-MAC.md`.
