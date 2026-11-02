#!/usr/bin/env bash
# Menjalankan Bumi Mandiri di macOS:   bash start-mac.sh
# Ganti port:                          PORT=8001 bash start-mac.sh
set -euo pipefail
cd "$(dirname "$0")"

PORT="${PORT:-8000}"
have() { command -v "$1" >/dev/null 2>&1; }
env_get() { grep -E "^$1=" .env 2>/dev/null | head -1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//' || true; }

[ -f .env ] && [ -d vendor ] || { echo "Project belum di-setup. Jalankan dulu:  bash setup-mac.sh" >&2; exit 1; }

# Nyalakan MySQL (Homebrew) bila perlu.
if [ "$(env_get DB_CONNECTION)" = "mysql" ] && have mysqladmin; then
  export MYSQL_PWD="$(env_get DB_PASSWORD)"
  H="$(env_get DB_HOST)"; P="$(env_get DB_PORT)"; U="$(env_get DB_USERNAME)"
  if ! mysqladmin -h "${H:-127.0.0.1}" -P "${P:-3306}" -u "${U:-root}" ping >/dev/null 2>&1; then
    if have brew && brew list mysql >/dev/null 2>&1; then
      echo "Menyalakan MySQL…"
      brew services start mysql >/dev/null
      for _ in $(seq 1 30); do
        mysqladmin -h "${H:-127.0.0.1}" -P "${P:-3306}" -u "${U:-root}" ping >/dev/null 2>&1 && break
        sleep 1
      done
    else
      echo "Peringatan: MySQL tidak terdeteksi berjalan. Nyalakan MySQL (MAMP/DBngin/Herd) lebih dulu." >&2
    fi
  fi
fi

echo "Website berjalan di http://localhost:${PORT}   (Ctrl+C untuk berhenti)"
have open && { (sleep 2; open "http://localhost:${PORT}") & }
exec php artisan serve --host=127.0.0.1 --port="$PORT"
