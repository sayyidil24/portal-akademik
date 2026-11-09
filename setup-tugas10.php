<?php

/**
 * Skrip bantu Tugas 10: mengedit 4 file bawaan secara otomatis.
 * Jalankan dari folder root proyek:  php setup-tugas10.php
 * Aman dijalankan berulang kali (bagian yang sudah diubah akan dilewati).
 * Skrip ini boleh dihapus setelah selesai.
 */

$root = rtrim($argv[1] ?? __DIR__, '/\\');

function baca(string $path): ?string
{
    return is_file($path) ? file_get_contents($path) : null;
}

function lapor(string $berkas, string $status): void
{
    echo str_pad($berkas, 32).' : '.$status.PHP_EOL;
}

// 1) app/Models/User.php : aktifkan trait HasRoles dari Spatie
$file = $root.'/app/Models/User.php';
$isi = baca($file);

if ($isi === null) {
    lapor('app/Models/User.php', 'GAGAL, file tidak ditemukan');
} elseif (str_contains($isi, 'HasRoles')) {
    lapor('app/Models/User.php', 'dilewati, HasRoles sudah ada');
} else {
    $isi = str_replace(
        "use Illuminate\\Notifications\\Notifiable;",
        "use Illuminate\\Notifications\\Notifiable;\nuse Spatie\\Permission\\Traits\\HasRoles;",
        $isi,
        $impor
    );
    $isi = str_replace('use HasFactory, Notifiable;', 'use HasFactory, HasRoles, Notifiable;', $isi, $trait);

    if ($impor === 1 && $trait === 1) {
        file_put_contents($file, $isi);
        lapor('app/Models/User.php', 'OK, HasRoles ditambahkan');
    } else {
        lapor('app/Models/User.php', 'GAGAL, tambahkan manual: use HasRoles; di dalam class User');
    }
}

// 2) bootstrap/app.php : daftarkan alias middleware role, permission, role_or_permission
$file = $root.'/bootstrap/app.php';
$isi = baca($file);

if ($isi === null) {
    lapor('bootstrap/app.php', 'GAGAL, file tidak ditemukan');
} elseif (str_contains($isi, 'RoleMiddleware')) {
    lapor('bootstrap/app.php', 'dilewati, alias sudah ada');
} else {
    $alias = <<<'PHP'
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
PHP;

    $pola = '/(->withMiddleware\(function \(Middleware \$middleware\)[^{]*\{)([ \t]*\R[ \t]*\/\/[ \t]*\R[ \t]*)?/';
    $isi = preg_replace_callback($pola, function ($m) use ($alias) {
        return $m[1]."\n".$alias.(isset($m[2]) && $m[2] !== '' ? "\n    " : '');
    }, $isi, 1, $jumlah);

    if ($jumlah === 1) {
        file_put_contents($file, $isi);
        lapor('bootstrap/app.php', 'OK, alias middleware ditambahkan');
    } else {
        lapor('bootstrap/app.php', 'GAGAL, tambahkan manual di withMiddleware (lihat panduan)');
    }
}

// 3) config/adminlte.php : judul, logo, URL dashboard, dan isi menu sidebar
$file = $root.'/config/adminlte.php';
$isi = baca($file);

if ($isi === null) {
    lapor('config/adminlte.php', 'GAGAL, jalankan dulu: php artisan adminlte:install');
} elseif (str_contains($isi, 'KHUSUS ADMIN')) {
    lapor('config/adminlte.php', 'dilewati, sudah diubah');
} else {
    $menu = <<<'PHP'
    'menu' => [
        // Navbar items:
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        ['header' => 'MENU UTAMA'],
        [
            'text' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'text' => 'Data Project',
            'route' => 'admin.projects.index',
            'icon' => 'fas fa-fw fa-folder-open',
            'can' => 'lihat-data',
        ],
        ['header' => 'KHUSUS ADMIN'],
        [
            'text' => 'Hapus Data',
            'route' => 'admin.projects.index',
            'icon' => 'fas fa-fw fa-trash',
            'icon_color' => 'red',
            'can' => 'hapus-data',
        ],
        ['header' => 'LAINNYA'],
        [
            'text' => 'Portal Utama',
            'url' => '/',
            'icon' => 'fas fa-fw fa-home',
        ],
    ],

PHP;

    $isi = str_replace("'title' => 'AdminLTE 3',", "'title' => 'Portal Akademik ITS',", $isi, $a);
    $isi = str_replace("'logo' => '<b>Admin</b>LTE',", "'logo' => '<b>Portal</b> Akademik',", $isi, $b);
    $isi = str_replace("'dashboard_url' => 'home',", "'dashboard_url' => 'admin/dashboard',", $isi, $c);

    $isi = preg_replace_callback(
        '/^[ \t]*\'menu\' => \[.*?^[ \t]*\],\R\R(?=[ \t]*\/\*\R[ \t]*\|-+\R[ \t]*\| Menu Filters)/ms',
        fn () => $menu."\n",
        $isi,
        1,
        $d
    );

    if ($a === 1 && $b === 1 && $c === 1 && $d === 1) {
        file_put_contents($file, $isi);
        lapor('config/adminlte.php', 'OK, judul, logo, dashboard_url, dan menu diubah');
    } else {
        lapor('config/adminlte.php', "GAGAL sebagian (title=$a logo=$b url=$c menu=$d), tidak ada yang disimpan");
    }
}

// 4) resources/views/auth/login.blade.php : sisipkan tombol login instan
$file = $root.'/resources/views/auth/login.blade.php';
$isi = baca($file);

if ($isi === null) {
    lapor('auth/login.blade.php', 'GAGAL, pasang Breeze terlebih dahulu');
} elseif (str_contains($isi, "auth.akun-uji")) {
    lapor('auth/login.blade.php', 'dilewati, sudah ada');
} else {
    $isi = str_replace('</x-guest-layout>', "    @include('auth.akun-uji')\n</x-guest-layout>", $isi, $jumlah);

    if ($jumlah === 1) {
        file_put_contents($file, $isi);
        lapor('auth/login.blade.php', 'OK, tombol akun uji ditambahkan');
    } else {
        lapor('auth/login.blade.php', "GAGAL, tambahkan manual: @include('auth.akun-uji') sebelum </x-guest-layout>");
    }
}

echo PHP_EOL.'Selesai. Jalankan: php artisan optimize:clear'.PHP_EOL;
