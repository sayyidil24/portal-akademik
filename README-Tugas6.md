
---

## Tugas 6: Database Akademik (Migration, Model, Factory, Seeder)

Tugas lanjutan: data portofolio kini disimpan di database MySQL, bukan lagi di variabel controller.

### Skema

| Tabel | Kolom penting |
|---|---|
| `users` | Tabel bawaan Laravel Breeze: `name`, `email` (unique), `password` |
| `projects` | `user_id` (FK ke `users.id`, cascade delete), `judul`, `deskripsi` (nullable), `tema_agent` (default `Ollama`), `api_key_secure` (terenkripsi) |

- Model `Project` memakai `$fillable` untuk melindungi dari mass-assignment. `user_id` sengaja tidak termasuk, sehingga pemilik project diisi lewat relasi dan tidak bisa dipalsukan dari input.
- `api_key_secure` memakai cast `encrypted` dan disembunyikan lewat `$hidden`, jadi tidak muncul di array atau JSON.

### Challenge

- `ProjectFactory` menghasilkan ide proposal Agentic AI yang realistis.
- `DatabaseSeeder` membuat 15 mahasiswa, masing-masing dengan 2 project, dan aman dijalankan ulang (tanpa error duplikasi).
- Akun demo penguji: `dosenpbkk@its.ac.id` dengan password `password` (3 project siap presentasi).
- Dashboard (`/dashboard`) hanya menampilkan project milik user yang login, terbaru lebih dulu.

### Setup Database Lokal

Prasyarat: PHP, Composer, Node.js, dan server MySQL yang berjalan.

```bash
git clone https://github.com/[USERNAME]/[NAMA-REPO].git
cd [NAMA-REPO]

composer install
npm install

cp .env.example .env        # di Windows PowerShell: copy .env.example .env
php artisan key:generate
```

Buat database kosong bernama `portal_akademik` di MySQL, lalu atur `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_akademik
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Untuk mengulang dari awal: `php artisan migrate:fresh --seed`.

Jalankan aplikasi dengan `php artisan serve` dan `npm run dev`, lalu login di `/login` memakai akun demo di atas.

### Pengujian

```bash
php artisan test --filter=ProjectDatabaseTest
```
