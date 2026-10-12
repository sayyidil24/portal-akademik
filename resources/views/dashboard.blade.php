<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Dashboard Portofolio
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <x-status-banner tipe="info">
                Halo, {{ Auth::user()->name }}! Berikut rancangan ide Agentic AI milikmu.
            </x-status-banner>

            <div class="grid gap-4 sm:grid-cols-3">
                <x-info-card judul="Total Project">{{ $projects->count() }}</x-info-card>
                <x-info-card judul="Project Terbaru">{{ $projects->first()?->judul ?? 'Belum ada' }}</x-info-card>
                <x-info-card judul="Email">
                    <span class="break-all text-base">{{ Auth::user()->email }}</span>
                </x-info-card>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse ($projects as $project)
                    <article class="rounded-lg border border-teal-500 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-semibold text-gray-900">{{ $project->judul }}</h3>
                            <span class="shrink-0 rounded-full bg-teal-100 px-3 py-1 text-xs font-medium text-teal-800">
                                {{ $project->tema_agent }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-gray-600">
                            {{ $project->deskripsi ?? 'Belum ada deskripsi.' }}
                        </p>

                        {{-- API key tidak pernah ditampilkan; hanya status penyimpanannya --}}
                        <dl class="mt-4 space-y-1 text-xs text-gray-500">
                            <div class="flex justify-between gap-3">
                                <dt>API key</dt>
                                <dd>Tersimpan terenkripsi</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt>Dibuat</dt>
                                <dd>{{ $project->created_at->format('d M Y, H:i') }}</dd>
                            </div>
                        </dl>
                    </article>
                @empty
                    <p class="text-gray-500 md:col-span-2">Belum ada project. Jalankan seeder atau tambahkan ide baru.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
