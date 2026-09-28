@extends('layouts.app')

@section('title', 'Ide Riset Agentic AI')

@section('konten')
    <h1 class="mb-2 text-2xl font-bold">Ide Riset: Platform Agentic AI</h1>
    <p class="mb-6 text-slate-600 dark:text-slate-300">
        Rancangan alur kerja platform agen AI yang diusulkan kelompok.
    </p>

    {{-- Visualisasi rancangan platform --}}
    <ol class="mb-10 grid gap-3 md:grid-cols-4">
        @foreach ($alur as $langkah)
            <li class="rounded-lg border border-teal-500 bg-white p-4 shadow-sm dark:bg-slate-800">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white">
                    {{ $loop->iteration }}
                </span>
                <h3 class="mt-2 font-semibold">{{ $langkah['judul'] }}</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $langkah['deskripsi'] }}</p>
            </li>
        @endforeach
    </ol>

    {{-- Notifikasi form memakai <x-status-banner> --}}
    @if (session('status'))
        <x-status-banner tipe="success" class="mb-6">{{ session('status') }}</x-status-banner>
    @endif

    @if ($errors->any())
        <x-status-banner tipe="error" class="mb-6">
            Periksa kembali isian formulir:
            <ul class="mt-1 list-inside list-disc text-sm font-normal">
                @foreach ($errors->all() as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </x-status-banner>
    @endif

    <h2 class="mb-3 text-xl font-semibold">Kirim Ide Baru</h2>
    <form method="POST" action="{{ route('ide-agent.kirim') }}"
          class="space-y-4 rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
        @csrf
        <input type="hidden" name="mode" value="{{ $mode }}">

        <div>
            <label for="nama" class="mb-1 block text-sm font-medium">Nama</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                   class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 dark:border-slate-600 dark:bg-slate-700">
        </div>

        <div>
            <label for="ide" class="mb-1 block text-sm font-medium">Ide Riset</label>
            <textarea id="ide" name="ide" rows="4"
                      class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 dark:border-slate-600 dark:bg-slate-700">{{ old('ide') }}</textarea>
        </div>

        <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 font-medium text-white hover:bg-teal-700">
            Kirim Ide
        </button>
    </form>
@endsection
