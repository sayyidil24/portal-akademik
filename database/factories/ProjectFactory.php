<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Data dummy ide proposal Agentic AI yang terlihat realistis.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $peran = [
            'Asisten Riset',
            'Pemeriksa Kode',
            'Perencana Studi',
            'Peninjau Jurnal',
            'Penjadwal Kuliah',
            'Pembimbing Tugas Akhir',
            'Analis Data Akademik',
            'Penerjemah Dokumen Teknis',
        ];

        $domain = [
            'Praktikum Basis Data',
            'Penelitian Machine Learning',
            'Kompetisi Pemrograman',
            'Administrasi Departemen',
            'Perpustakaan Digital Kampus',
            'Tugas Akhir Mahasiswa',
            'Kelas Pemrograman Web',
            'Kolaborasi Kelompok Belajar',
        ];

        $tujuan = [
            'memecah target belajar menjadi sub-tugas mingguan dan memantau progresnya',
            'membaca beberapa jurnal lalu menyusun ringkasan serta daftar celah penelitian',
            'menjalankan pengujian pada kode yang dikumpulkan dan memberi umpan balik terstruktur',
            'menelusuri sumber referensi, memverifikasi sitasi, dan menyusun daftar pustaka',
            'menyusun jadwal belajar adaptif berdasarkan tenggat dan beban tugas',
            'mengubah catatan kuliah menjadi kuis latihan beserta pembahasannya',
        ];

        $tema = ['Ollama', 'LangChain', 'CrewAI', 'AutoGen', 'LlamaIndex', 'OpenAI API'];

        $namaPeran = fake()->randomElement($peran);
        $namaDomain = fake()->randomElement($domain);
        $namaTema = fake()->randomElement($tema);

        return [
            'user_id' => User::factory(),
            'judul' => "{$namaPeran} untuk {$namaDomain}",
            'deskripsi' => sprintf(
                'Agen AI otonom untuk %s yang bertugas %s. Alur kerja memakai pola planner, executor, dan reviewer dengan %s sebagai mesin utama.',
                strtolower($namaDomain),
                fake()->randomElement($tujuan),
                $namaTema
            ),
            'tema_agent' => $namaTema,
            // Kunci palsu untuk keperluan demo, bukan kredensial sungguhan
            'api_key_secure' => 'sk-dummy-'.fake()->regexify('[A-Za-z0-9]{32}'),
        ];
    }
}
