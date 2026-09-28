# Tugas 4: Portal Akademik Multi-View (Laravel + Blade + Vite + Tailwind)

File di folder ini adalah lapisan di atas proyek Laravel baru. Salin ke root proyekmu dan timpa file yang sama.

## Langkah Setup

```bash
composer create-project laravel/laravel portal-akademik
cd portal-akademik

# Salin isi folder ini ke root proyek (timpa file yang sudah ada), lalu:
npm install
npm install tailwindcss @tailwindcss/vite   # lewati jika sudah ada di package.json

# Agar form tidak butuh database (materi DB pekan depan), ubah di .env:
# SESSION_DRIVER=file

php artisan serve      # terminal 1
npm run dev            # terminal 2 (wajib, kalau tidak CSS kosong)
```

## URL untuk Uji Manual

| URL | Hasil |
|---|---|
| `/` atau `/beranda` | Beranda |
| `/beranda?user=Andi` | Beranda + alert selamat datang (Challenge 2) |
| `/profil-mahasiswa` | Profil dengan `<x-info-card>` |
| `/ide-agent` | Visualisasi alur + formulir ide |
| `/ide-agent?mode=dark` | Mode gelap (Challenge 1) |

## Menjalankan Tes

```bash
php artisan test --filter=PageTest
```

## Pengumpulan (slide 43)

```bash
git init
git add .
git commit -m "Tugas 4: portal akademik multi-view"
git branch -M main
git remote add origin https://github.com/<username>/<repo>.git
git push -u origin main
```

Pastikan `.gitignore` bawaan Laravel masih memuat `/vendor` dan `.env`. Cek dengan `git status`: keduanya tidak boleh muncul. Kirim link repo ke LMS ITS sebelum H-1.
