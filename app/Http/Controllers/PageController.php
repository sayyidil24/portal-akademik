<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Beranda. Challenge 2: /beranda?user=Andi menampilkan alert selamat datang.
     */
    public function beranda(Request $request)
    {
        $mode = $this->mode($request);

        $user = trim((string) $request->query('user', ''));
        $user = $user !== '' ? mb_substr($user, 0, 50) : null;

        $fitur = [
            ['judul' => 'Profil Mahasiswa', 'deskripsi' => 'Data diri dan minat riset.', 'rute' => 'profil'],
            ['judul' => 'Ide Riset Agentic AI', 'deskripsi' => 'Rancangan platform dan formulir ide.', 'rute' => 'ide-agent'],
        ];

        return view('beranda', compact('user', 'fitur', 'mode'));
    }

    /**
     * Profil. GANTI isi array ini dengan data dirimu sendiri.
     */
    public function profil(Request $request)
    {
        $mode = $this->mode($request);

        $profil = [
            'Nama Lengkap' => 'Muhammad Sayyidil Anam',
            'NRP' => '5025241267',
            'Departemen' => 'Teknik Informatika',
            'Mata Kuliah' => 'Pemrograman Berbasis Kerangka Kerja (PBKK)',
        ];

        $minat = ['Agentic AI', 'Machine Learning', 'Basis Data', 'Competitive Programming'];

        return view('profil', compact('profil', 'minat', 'mode'));
    }

    /**
     * Ide-Riset. Challenge 1: /ide-agent?mode=dark mengaktifkan dark mode.
     */
    public function ideAgent(Request $request)
    {
        $mode = $this->mode($request);

        $alur = [
            ['judul' => 'Planner Agent', 'deskripsi' => 'Memecah tujuan pengguna menjadi daftar sub-tugas terurut.'],
            ['judul' => 'Tool Executor', 'deskripsi' => 'Menjalankan tiap sub-tugas lewat tool: pencarian, kode, berkas.'],
            ['judul' => 'Reviewer Agent', 'deskripsi' => 'Memeriksa hasil, lalu meminta perbaikan jika belum sesuai.'],
            ['judul' => 'Laporan Akhir', 'deskripsi' => 'Merangkum hasil dan menyajikannya kepada pengguna.'],
        ];

        return view('ide-agent', compact('alur', 'mode'));
    }

    /**
     * Menerima formulir ide (POST + CSRF), validasi, lalu redirect dengan flash message.
     */
    public function kirimIde(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'ide'  => ['required', 'string', 'min:10', 'max:1000'],
            'mode' => ['nullable', 'in:light,dark'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max'      => 'Nama maksimal :max karakter.',
            'ide.required'  => 'Ide riset wajib diisi.',
            'ide.min'       => 'Ide riset minimal :min karakter.',
            'ide.max'       => 'Ide riset maksimal :max karakter.',
        ]);

        // Pertahankan mode gelap setelah redirect
        $params = ($data['mode'] ?? null) === 'dark' ? ['mode' => 'dark'] : [];

        return redirect()
            ->route('ide-agent', $params)
            ->with('status', "Terima kasih, {$data['nama']}! Ide risetmu berhasil dikirim.");
    }

    /**
     * Normalisasi parameter ?mode= menjadi 'dark' atau 'light'.
     */
    private function mode(Request $request): string
    {
        return $request->query('mode') === 'dark' ? 'dark' : 'light';
    }
}
