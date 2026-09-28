@extends('layouts.app')

@section('title', 'Beranda')

@section('konten')
    {{-- Challenge 2: alert dinamis dari parameter ?user= (otomatis di-escape oleh {{ }}) --}}
    @if ($user)
        <x-status-banner tipe="success" class="mb-6">
            Selamat datang, {{ $user }}! Senang melihatmu di Portal Akademik.
        </x-status-banner>
    @endif

    <section class="mb-8 rounded-xl bg-white p-8 shadow-sm dark:bg-slate-800">
        <h1 class="text-3xl font-bold">Portal Akademik Mahasiswa</h1>
        <p class="mt-2 text-slate-600 dark:text-slate-300">
            Mini-website untuk profil diri dan rancangan platform Agentic AI kelompok.
        </p>
    </section>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($fitur as $f)
            <a href="{{ route($f['rute'], request()->only('mode')) }}" class="block">
                <x-info-card :judul="'Menu ' . $loop->iteration" class="h-full transition hover:shadow-md">
                    {{ $f['judul'] }}
                    <span class="mt-1 block text-sm font-normal text-slate-500 dark:text-slate-400">{{ $f['deskripsi'] }}</span>
                </x-info-card>
            </a>
        @endforeach
    </div>
@endsection
