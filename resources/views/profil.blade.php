@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('konten')
    <h1 class="mb-6 text-2xl font-bold">Profil Mahasiswa</h1>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($profil as $label => $nilai)
            <x-info-card :judul="$label">{{ $nilai }}</x-info-card>
        @endforeach
    </div>

    <h2 class="mb-3 mt-8 text-xl font-semibold">Minat Riset</h2>
    <ul class="flex flex-wrap gap-2">
        @forelse ($minat as $m)
            <li class="rounded-full bg-teal-100 px-3 py-1 text-sm text-teal-800 dark:bg-teal-900/50 dark:text-teal-100">{{ $m }}</li>
        @empty
            <li class="text-slate-500 dark:text-slate-400">Minat riset belum diisi.</li>
        @endforelse
    </ul>
@endsection
