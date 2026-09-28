# Tugas 4: Portal Akademik Multi-View 

## Identitas

| | |
|---|---|
| Nama | Muhammad Sayyidil Anam |
| NRP | 5025241267 |
| Kelas | B |

## Deskripsi

Mini-website akademik pribadi yang terdiri dari tiga halaman: Beranda, Profil Mahasiswa, dan Ide Riset (visualisasi rancangan platform Agentic AI beserta formulir pengumpulan ide). Seluruh halaman dikelola oleh satu master layout Blade, sehingga tidak ada struktur HTML yang ditulis berulang di file view.

## Teknologi

- Laravel 13 (Blade templating)
- Tailwind CSS, dikelola lewat NPM dan dikompilasi oleh Vite secara lokal (tanpa CDN)
- PHPUnit untuk pengujian fitur

## Struktur Utama

```
app/Http/Controllers/PageController.php     Satu controller untuk semua halaman
routes/web.php                              Definisi rute
resources/views/layouts/app.blade.php       Master layout (title dinamis, navbar, konten, footer)
resources/views/beranda.blade.php           Halaman Beranda
resources/views/profil.blade.php            Halaman Profil
resources/views/ide-agent.blade.php         Halaman Ide Riset + formulir
resources/views/components/info-card.blade.php       Komponen kartu data profil
resources/views/components/status-banner.blade.php   Komponen notifikasi
resources/css/app.css, vite.config.js       Konfigurasi aset
tests/Feature/PageTest.php                  Pengujian otomatis
```

## Rute

| Metode | URL | Fungsi |
|---|---|---|
| GET | `/` dan `/beranda` | Beranda |
| GET | `/profil-mahasiswa` | Profil mahasiswa |
| GET | `/ide-agent` | Visualisasi alur dan formulir ide |
| POST | `/ide-agent` | Menerima formulir, validasi, lalu redirect dengan pesan status |


## Pemenuhan Spesifikasi dan Rubrik

| Kriteria | Implementasi |
|---|---|
| Pewarisan layout (`@extends`) | Ketiga halaman memakai `@extends('layouts.app')` dengan `@section('title')` dan `@section('konten')`. |
| Blade components dan slots | `<x-info-card>` (props `judul`, slot isi) dipakai di Profil dan Beranda. `<x-status-banner>` (props `tipe`, slot pesan) dipakai untuk alert form dan sambutan. Keduanya memakai `$attributes->merge()`. |
| Vite asset bundling | Tailwind dipasang lewat NPM, dikompilasi Vite, dan dimuat dengan `@vite([...])` di layout. |
| Desain UI dan kerapian | Layout responsif berbasis grid dan flexbox, navbar dengan menu aktif otomatis, tampilan terang dan gelap. |
| Challenge | Kedua tantangan diselesaikan (lihat bagian di bawah). |

## Challenge

**1. Toggle tema dinamis.** Controller membaca `?mode=` dan meneruskan `light` atau `dark` ke view. Layout memasang class `dark` pada elemen `<html>` lewat variabel Blade, dan seluruh elemen memakai varian `dark:` Tailwind. Contoh: `/ide-agent?mode=dark`. Mode gelap tetap terjaga saat berpindah halaman dan setelah formulir dikirim.

**2. Alert status interaktif.** Parameter `?user=` ditampilkan lewat komponen `<x-status-banner>`. Contoh: `/beranda?user=Andi` menampilkan "Selamat datang, Andi!". Nilai parameter di-escape oleh sintaks `{{ }}`, sehingga input berisi tag HTML tidak dieksekusi.

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

## Tangkapan Layar

### Beranda
<img width="1916" height="914" alt="image" src="https://github.com/user-attachments/assets/589bcfbc-25b0-4427-a688-212075e49cb8" />

### Profil Mahasiswa
<img width="1919" height="913" alt="image" src="https://github.com/user-attachments/assets/5e341a66-da60-4ad2-b067-8149d323facf" />

### Ide Riset
<img width="1898" height="913" alt="image" src="https://github.com/user-attachments/assets/d5c6d374-7968-4ebe-b3d2-813f96554ac5" />

### Mode Gelap
<img width="1919" height="913" alt="image" src="https://github.com/user-attachments/assets/5a79da19-84ac-4218-bcb8-5bb2b3cf5076" />
