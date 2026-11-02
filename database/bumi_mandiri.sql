-- =============================================================================
--  BUMI MANDIRI — STRUKTUR DATABASE  (MySQL 8+ / MariaDB, utf8mb4)
--
--  Sumber : database/migrations/ (Laravel 11). SQL di bawah dihasilkan oleh
--           Laravel (migrate --pretend) lalu diverifikasi dengan membandingkannya
--           dengan hasil "php artisan migrate" pada database sungguhan.
--  Isi    : STRUKTUR SAJA (tidak ada data). Data awal: php artisan db:seed
--
--  Cara memakai (pilih salah satu):
--    Terminal :  mysql -u root -p < bumi_mandiri.sql
--    phpMyAdmin / TablePlus / DBeaver :  Import / jalankan file ini
--
--  CATATAN
--   * File ini membuat database `bumi_mandiri` dan semua tabelnya. Jika tabel
--     sudah ada, perintah CREATE TABLE akan gagal (data tidak pernah dihapus).
--   * Tabel `migrations` ikut diisi, sehingga "php artisan migrate" sesudahnya
--     menjawab "Nothing to migrate". Jangan memakai file ini bersamaan dengan
--     "php artisan migrate" pada database yang sama dari awal.
--   * Perubahan struktur ke depan sebaiknya lewat file migration Laravel.
-- =============================================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `bumi_mandiri`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bumi_mandiri`;

-- ---------------------------------------------------------------------------
-- migrations: pencatat migrasi Laravel
-- ---------------------------------------------------------------------------
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- #### migration: 2019_08_19_000000_create_failed_jobs_table

-- ---------------------------------------------------------------------------
-- failed_jobs: Job antrian yang gagal (bawaan Laravel)
-- ---------------------------------------------------------------------------
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `failed_jobs`
  ADD UNIQUE `failed_jobs_uuid_unique`(`uuid`);

-- #### migration: 2019_12_14_000001_create_personal_access_tokens_table

-- ---------------------------------------------------------------------------
-- personal_access_tokens: Token API Laravel Sanctum (bawaan Laravel)
-- ---------------------------------------------------------------------------
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text NULL,
  `last_used_at` timestamp NULL,
  `expires_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `personal_access_tokens`
  ADD INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`);
ALTER TABLE `personal_access_tokens`
  ADD UNIQUE `personal_access_tokens_token_unique`(`token`);

-- #### migration: 2024_01_01_000001_create_roles_table

-- ---------------------------------------------------------------------------
-- roles: Peran pengguna (admin, user)
-- ---------------------------------------------------------------------------
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `roles`
  ADD UNIQUE `roles_name_unique`(`name`);

-- #### migration: 2024_01_01_000002_create_users_table

-- ---------------------------------------------------------------------------
-- users: Akun pengguna dan admin
-- ---------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `role_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NULL,
  `avatar` varchar(255) NULL,
  `email_verified_at` timestamp NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
ALTER TABLE `users`
  ADD UNIQUE `users_email_unique`(`email`);
-- ---------------------------------------------------------------------------
-- password_reset_tokens: Token reset password (bawaan Laravel)
-- ---------------------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL,
  PRIMARY KEY (`email`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- sessions: Sesi login (SESSION_DRIVER=database)
-- ---------------------------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned NULL,
  `ip_address` varchar(45) NULL,
  `user_agent` text NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `sessions`
  ADD INDEX `sessions_user_id_index`(`user_id`);
ALTER TABLE `sessions`
  ADD INDEX `sessions_last_activity_index`(`last_activity`);

-- #### migration: 2024_01_01_000003_create_categories_table

-- ---------------------------------------------------------------------------
-- categories: Kategori aktivitas
-- ---------------------------------------------------------------------------
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) NULL,
  `description` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `categories`
  ADD UNIQUE `categories_slug_unique`(`slug`);

-- #### migration: 2024_01_01_000004_create_destinations_table

-- ---------------------------------------------------------------------------
-- destinations: Destinasi wisata
-- ---------------------------------------------------------------------------
CREATE TABLE `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NULL,
  `location` varchar(255) NULL,
  `cover_image` varchar(255) NULL,
  `latitude` decimal(10, 7) NULL,
  `longitude` decimal(10, 7) NULL,
  `google_maps_url` varchar(255) NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `destinations`
  ADD UNIQUE `destinations_slug_unique`(`slug`);

-- #### migration: 2024_01_01_000005_create_activities_table

-- ---------------------------------------------------------------------------
-- activities: Aktivitas outdoor yang dapat dibooking
-- ---------------------------------------------------------------------------
CREATE TABLE `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` varchar(255) NULL,
  `description` longtext NULL,
  `price` decimal(12, 2) NOT NULL DEFAULT '0',
  `duration_minutes` int NOT NULL DEFAULT '60',
  `capacity` int NOT NULL DEFAULT '10',
  `cover_image` varchar(255) NULL,
  `gallery` json NULL,
  `rating_avg` decimal(3, 2) NOT NULL DEFAULT '0',
  `review_count` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `activities`
  ADD CONSTRAINT `activities_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;
ALTER TABLE `activities`
  ADD INDEX `activities_is_active_is_featured_index`(`is_active`, `is_featured`);
ALTER TABLE `activities`
  ADD UNIQUE `activities_slug_unique`(`slug`);

-- #### migration: 2024_01_01_000006_create_packages_table

-- ---------------------------------------------------------------------------
-- packages: Paket wisata
-- ---------------------------------------------------------------------------
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `target_segment` varchar(255) NULL,
  `description` text NULL,
  `base_price` decimal(12, 2) NOT NULL DEFAULT '0',
  `min_participants` int NOT NULL DEFAULT '1',
  `max_participants` int NULL,
  `estimated_duration` varchar(255) NULL,
  `cover_image` varchar(255) NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `packages`
  ADD UNIQUE `packages_slug_unique`(`slug`);
-- ---------------------------------------------------------------------------
-- package_activity: Tabel penghubung paket <-> aktivitas (many-to-many)
-- ---------------------------------------------------------------------------
CREATE TABLE `package_activity` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `package_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `package_activity`
  ADD CONSTRAINT `package_activity_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;
ALTER TABLE `package_activity`
  ADD CONSTRAINT `package_activity_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

-- #### migration: 2024_01_01_000007_create_bookings_table

-- ---------------------------------------------------------------------------
-- bookings: Pemesanan (booking) oleh user
-- ---------------------------------------------------------------------------
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `booking_code` varchar(255) NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NULL,
  `participants` int NOT NULL,
  `subtotal` decimal(14, 2) NOT NULL DEFAULT '0',
  `discount` decimal(14, 2) NOT NULL DEFAULT '0',
  `total_price` decimal(14, 2) NOT NULL DEFAULT '0',
  `status` enum('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `notes` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL;
ALTER TABLE `bookings`
  ADD INDEX `bookings_user_id_status_index`(`user_id`, `status`);
ALTER TABLE `bookings`
  ADD UNIQUE `bookings_booking_code_unique`(`booking_code`);
-- ---------------------------------------------------------------------------
-- booking_items: Rincian aktivitas dalam satu booking
-- ---------------------------------------------------------------------------
CREATE TABLE `booking_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `booking_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(12, 2) NOT NULL,
  `subtotal` decimal(14, 2) NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;

-- #### migration: 2024_01_01_000008_create_payments_table

-- ---------------------------------------------------------------------------
-- payments: Pembayaran untuk booking
-- ---------------------------------------------------------------------------
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `booking_id` bigint unsigned NOT NULL,
  `payment_code` varchar(255) NOT NULL,
  `amount` decimal(14, 2) NOT NULL,
  `payment_method` varchar(255) NULL,
  `payment_status` enum('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL,
  `notes` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
ALTER TABLE `payments`
  ADD UNIQUE `payments_payment_code_unique`(`payment_code`);

-- #### migration: 2024_01_01_000009_create_reviews_and_favorites_table

-- ---------------------------------------------------------------------------
-- reviews: Ulasan user untuk aktivitas (tampil setelah is_approved = 1)
-- ---------------------------------------------------------------------------
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `booking_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `comment` text NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
ALTER TABLE `reviews`
  ADD UNIQUE `reviews_booking_id_activity_id_unique`(`booking_id`, `activity_id`);
-- ---------------------------------------------------------------------------
-- favorites: Aktivitas favorit milik user
-- ---------------------------------------------------------------------------
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE;
ALTER TABLE `favorites`
  ADD UNIQUE `favorites_user_id_activity_id_unique`(`user_id`, `activity_id`);

-- #### migration: 2024_01_01_000010_create_content_and_system_tables

-- ---------------------------------------------------------------------------
-- articles: Artikel / blog
-- ---------------------------------------------------------------------------
CREATE TABLE `articles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `author_id` bigint unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `cover_image` varchar(255) NULL,
  `excerpt` text NULL,
  `content` longtext NOT NULL,
  `reading_time` int NOT NULL DEFAULT '3',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `articles`
  ADD CONSTRAINT `articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
ALTER TABLE `articles`
  ADD UNIQUE `articles_slug_unique`(`slug`);
-- ---------------------------------------------------------------------------
-- galleries: Galeri foto
-- ---------------------------------------------------------------------------
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` bigint unsigned NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
-- ---------------------------------------------------------------------------
-- contacts: Pesan dari formulir kontak
-- ---------------------------------------------------------------------------
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NULL,
  `subject` varchar(255) NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- settings: Pengaturan situs (pasangan key-value)
-- ---------------------------------------------------------------------------
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key` varchar(255) NOT NULL,
  `value` text NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `settings`
  ADD UNIQUE `settings_key_unique`(`key`);
-- ---------------------------------------------------------------------------
-- notifications: Notifikasi Laravel (relasi polimorfik)
-- ---------------------------------------------------------------------------
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `notifications`
  ADD INDEX `notifications_notifiable_type_notifiable_id_index`(`notifiable_type`, `notifiable_id`);

-- ---------------------------------------------------------------------------
-- Catatan migrasi yang sudah dijalankan
-- ---------------------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`) VALUES
  ('2019_08_19_000000_create_failed_jobs_table', 1),
  ('2019_12_14_000001_create_personal_access_tokens_table', 1),
  ('2024_01_01_000001_create_roles_table', 1),
  ('2024_01_01_000002_create_users_table', 1),
  ('2024_01_01_000003_create_categories_table', 1),
  ('2024_01_01_000004_create_destinations_table', 1),
  ('2024_01_01_000005_create_activities_table', 1),
  ('2024_01_01_000006_create_packages_table', 1),
  ('2024_01_01_000007_create_bookings_table', 1),
  ('2024_01_01_000008_create_payments_table', 1),
  ('2024_01_01_000009_create_reviews_and_favorites_table', 1),
  ('2024_01_01_000010_create_content_and_system_tables', 1);
