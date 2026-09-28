@php
    // $mode dikirim dari controller ('dark' atau 'light')
    $gelap = ($mode ?? 'light') === 'dark';
    $q = request()->only('mode'); // bawa ?mode=dark ke tautan navigasi
    $menu = [
        ['rute' => 'beranda', 'label' => 'Beranda'],
        ['rute' => 'profil', 'label' => 'Profil'],
        ['rute' => 'ide-agent', 'label' => 'Ide Riset'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $gelap ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Akademik') | Portal Akademik ITS</title>

    {{-- Aset dikompilasi lokal oleh Vite (tanpa CDN) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-800 dark:bg-slate-900 dark:text-slate-100">

    <nav class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a href="{{ route('beranda', $q) }}" class="font-bold text-teal-600 dark:text-teal-400">
                Portal Akademik ITS
            </a>

            <ul class="flex flex-wrap items-center gap-1 text-sm">
                @foreach ($menu as $item)
                    <li>
                        <a href="{{ route($item['rute'], $q) }}"
                           class="rounded-md px-3 py-2 {{ request()->routeIs($item['rute'] . '*') ? 'bg-teal-600 text-white' : 'hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ request()->fullUrlWithQuery(['mode' => $gelap ? null : 'dark']) }}"
                       class="rounded-md border border-slate-300 px-3 py-2 hover:bg-slate-100 dark:border-slate-600 dark:hover:bg-slate-700">
                        {{ $gelap ? 'Mode Terang' : 'Mode Gelap' }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-8">
        @yield('konten')
    </main>

    <footer class="border-t border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
        <div class="mx-auto max-w-5xl px-4 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
            &copy; {{ date('Y') }} Institut Teknologi Sepuluh Nopember, Surabaya. Departemen Teknik Informatika.
        </div>
    </footer>

</body>
</html>
