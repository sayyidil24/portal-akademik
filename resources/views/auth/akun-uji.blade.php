{{-- Tombol login instan untuk demo. Hanya tampil saat APP_ENV=local. --}}
@if (app()->environment('local'))
    <div class="mt-6 border-t border-gray-200 pt-4">
        <p class="mb-3 text-sm font-semibold text-gray-600">Akun uji (khusus demo lokal)</p>

        <div class="flex flex-col gap-2">
            @foreach ([
                'admin' => 'Masuk sebagai Admin',
                'staf' => 'Masuk sebagai Staf',
                'mahasiswa' => 'Masuk sebagai Mahasiswa (tanpa peran)',
            ] as $kunci => $label)
                <form method="POST" action="{{ route('demo-login', $kunci) }}">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-md border border-teal-600 px-4 py-2 text-sm font-medium text-teal-700 hover:bg-teal-50">
                        {{ $label }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@endif
