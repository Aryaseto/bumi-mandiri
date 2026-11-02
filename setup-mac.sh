#!/usr/bin/env bash
# =============================================================================
#  Bumi Mandiri — setup otomatis untuk macOS
#  Jalankan dari folder project:   bash setup-mac.sh
#
#  Opsi:
#    --fresh     hapus SEMUA tabel lalu migrate + seed ulang (data hilang!)
#    --rebuild   paksa build ulang CSS/JS (butuh Node.js)
#
#  Aman dijalankan berulang kali: .env, database, dan data yang sudah ada
#  tidak ditimpa kecuali Anda memakai --fresh.
# =============================================================================
set -euo pipefail
cd "$(dirname "$0")"

FRESH=0
REBUILD=0
for arg in "$@"; do
  case "$arg" in
    --fresh)   FRESH=1 ;;
    --rebuild) REBUILD=1 ;;
    -h|--help) sed -n '2,13p' "$0"; exit 0 ;;
    *) printf 'Opsi tidak dikenal: %s\n' "$arg" >&2; exit 1 ;;
  esac
done

say()  { printf '\n\033[1;32m==> %s\033[0m\n' "$*"; }
warn() { printf '\033[1;33m !  %s\033[0m\n' "$*"; }
die()  { printf '\n\033[1;31m XX %s\033[0m\n' "$*" >&2; exit 1; }
have() { command -v "$1" >/dev/null 2>&1; }

# Baca nilai dari .env (tanpa tanda kutip).
env_get() { grep -E "^$1=" .env 2>/dev/null | head -1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//' || true; }

brew_install() {
  have brew || die "'$1' belum terpasang dan Homebrew tidak ditemukan. Pasang Homebrew dulu dari https://brew.sh lalu jalankan ulang skrip ini."
  say "Memasang $1 lewat Homebrew…"
  brew install "$1"
}

[ "$(uname -s)" = "Darwin" ] || warn "Skrip ini ditujukan untuk macOS (terdeteksi: $(uname -s)). Dilanjutkan saja."

# ----------------------------------------------------------------------------
say "1/7  Memeriksa PHP & Composer"
have php      || brew_install php
have composer || brew_install composer
php -r 'exit(version_compare(PHP_VERSION, "8.2.0", ">=") ? 0 : 1);' \
  || die "PHP $(php -r 'echo PHP_VERSION;') terlalu lama. Project ini butuh PHP 8.2+  →  brew upgrade php"
echo "PHP $(php -r 'echo PHP_VERSION;') OK"

# ----------------------------------------------------------------------------
say "2/7  Memasang dependensi PHP (composer install)"
composer install --no-interaction --prefer-dist

# ----------------------------------------------------------------------------
say "3/7  Menyiapkan file .env"
if [ ! -f .env ]; then
  cp .env.example .env
  echo ".env dibuat dari .env.example"
else
  echo ".env sudah ada — dipakai apa adanya"
fi
if [ -z "$(env_get APP_KEY)" ]; then
  php artisan key:generate --force
fi

DB_CONNECTION="$(env_get DB_CONNECTION)"; DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="$(env_get DB_HOST)";             DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="$(env_get DB_PORT)";             DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="$(env_get DB_DATABASE)"
DB_USERNAME="$(env_get DB_USERNAME)";     DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="$(env_get DB_PASSWORD)"

# ----------------------------------------------------------------------------
say "4/7  Menyiapkan database ($DB_CONNECTION)"
if [ "$DB_CONNECTION" = "mysql" ]; then
  have mysql || brew_install mysql
  export MYSQL_PWD="$DB_PASSWORD"

  if ! mysqladmin -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" ping >/dev/null 2>&1; then
    if have brew && brew list mysql >/dev/null 2>&1; then
      warn "MySQL belum berjalan — menyalakan lewat Homebrew…"
      brew services start mysql >/dev/null
      for _ in $(seq 1 30); do
        mysqladmin -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" ping >/dev/null 2>&1 && break
        sleep 1
      done
    fi
  fi

  mysqladmin -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" ping >/dev/null 2>&1 \
    || die "Tidak bisa terhubung ke MySQL di $DB_HOST:$DB_PORT. Nyalakan MySQL (brew services start mysql / MAMP / DBngin), cek DB_HOST dan DB_PORT di .env, lalu jalankan ulang."

  mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" \
        -e "CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
    || die "Gagal membuat database '$DB_DATABASE'. Jika pesannya 'Access denied', isi DB_USERNAME / DB_PASSWORD di .env sesuai MySQL Anda, lalu jalankan ulang."
  echo "Database '$DB_DATABASE' siap"
else
  echo "Koneksi $DB_CONNECTION — pembuatan database dilewati"
fi

# ----------------------------------------------------------------------------
say "5/7  Migrasi & data awal"
if [ "$FRESH" = "1" ]; then
  warn "--fresh akan MENGHAPUS SEMUA TABEL di database '$DB_DATABASE'."
  read -r -p "Ketik 'ya' untuk melanjutkan: " ok
  [ "$ok" = "ya" ] || die "Dibatalkan."
  php artisan migrate:fresh --seed --force
else
  php artisan migrate --force || die "Migrasi gagal. Jika pesannya 'Table ... already exists' (sisa percobaan lama), jalankan:  bash setup-mac.sh --fresh"
  USERS="$(php artisan tinker --execute='echo \App\Models\User::count();' 2>/dev/null | tail -n 1 | tr -d '[:space:]')"
  if [ "$USERS" = "0" ]; then
    php artisan db:seed --force
  else
    echo "Database sudah berisi data (${USERS:-?} user) — seeder dilewati"
  fi
fi

# ----------------------------------------------------------------------------
say "6/7  Storage link & bersihkan cache"
php artisan storage:link --force >/dev/null
php artisan config:clear >/dev/null
php artisan view:clear   >/dev/null
echo "OK"

# ----------------------------------------------------------------------------
say "7/7  Aset frontend (Tailwind/Alpine)"
if [ "$REBUILD" = "1" ] || [ ! -f public/build/manifest.json ]; then
  have node || brew_install node
  have npm  || die "npm tidak ditemukan padahal Node sudah terpasang. Buka terminal baru lalu coba lagi."
  npm install
  npm run build
else
  echo "public/build sudah ada — build dilewati (pakai --rebuild jika Anda mengubah tampilan)"
fi

printf '\n\033[1;32mSelesai!\033[0m  Jalankan website dengan:\n\n'
printf '    bash start-mac.sh\n\n'
printf 'Website      http://localhost:8000\n'
printf 'Panel admin  http://localhost:8000/admin\n'
printf 'Login admin  admin@bumimandiri.id / password\n\n'
