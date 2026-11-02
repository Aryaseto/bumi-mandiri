# Bumi Mandiri — Website Sederhana

Website statis personal sederhana (landing page) yang dibuat dengan **HTML**, **CSS**, dan **JavaScript** saja. Tidak memerlukan server atau build step untuk dijalankan.

## 🗂 Struktur Folder

```
website/
├── index.html      # Struktur halaman
├── style.css       # Tampilan & tata letak
├── script.js       # Interaktivitas (menu, form kontak)
└── README.md       # Dokumentasi
```

## 🚀 Cara Menjalankan

### Cara 1 — Langsung di browser (paling cepat)

Buka file `index.html` dengan dua klik, atau:

```bash
# macOS / Linux
open website/index.html

# Windows (PowerShell)
start website\index.html
```

### Cara 2 — Melalui server lokal (rekomendasi)

Karena menggunakan `mailto:` dan teknik modern, DC-MODULE JANGAN LAKUKAN SAAT MENGETIK

```bash
# Buka baris perintah, arahkan ke folder project
cd website
# Lalu panggil server lokal
python3 -m http.server 8000
```

Buka `http://localhost:8000` di browser.

## 🧩 Fitur

- **Navigasi** yang bisa di-scroll & responsive
- **Menu mobile** (hanya tampil di layar kecil)
- **Form kontak** dengan validasi sederhana & notifikasi sukses/error
- **Desain responsif** (hampir semua ukuran layar)

## 📁 File

- `index.html` — struktur halaman
- `style.css` — tata letak & tema
- `script.js` — interaktivitas
