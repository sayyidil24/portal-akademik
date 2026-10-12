<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private const EMAIL_DEMO = 'dosenpbkk@its.ac.id';

    /**
     * Aman dijalankan berulang: data yang sudah ada dilewati sehingga tidak terjadi error duplikasi email.
     */
    public function run(): void
    {
        $this->seedMahasiswa();
        $this->seedAkunDemo();
    }

    /**
     * 15 mahasiswa acak, masing-masing memiliki 2 project dummy.
     */
    private function seedMahasiswa(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $email = "mahasiswa{$i}@student.its.ac.id";

            if (User::where('email', $email)->exists()) {
                continue;
            }

            $mahasiswa = User::factory()->create(['email' => $email]);

            Project::factory()->count(2)->for($mahasiswa)->create();
        }
    }

    /**
     * Akun penguji untuk demo: dosenpbkk@its.ac.id (password: password) dengan 3 project siap presentasi.
     */
    private function seedAkunDemo(): void
    {
        if (User::where('email', self::EMAIL_DEMO)->exists()) {
            return;
        }

        $dosen = User::factory()->create([
            'name' => 'Dosen PBKK',
            'email' => self::EMAIL_DEMO,
            'password' => Hash::make('password'),
        ]);

        $proyekDemo = [
            [
                'judul' => 'Agen Peninjau Jurnal Otomatis',
                'deskripsi' => 'Agen yang membaca kumpulan jurnal, mengekstrak metode dan hasil, lalu menyusun matriks perbandingan untuk tinjauan pustaka.',
                'tema_agent' => 'Ollama',
            ],
            [
                'judul' => 'Planner Studi Adaptif Mahasiswa Informatika',
                'deskripsi' => 'Agen perencana yang menyusun jadwal belajar mingguan dari tenggat tugas dan menyesuaikannya saat progres berubah.',
                'tema_agent' => 'LangChain',
            ],
            [
                'judul' => 'Asisten Debugging Praktikum Basis Data',
                'deskripsi' => 'Agen yang menjalankan kueri mahasiswa pada basis data sampel, membandingkan hasilnya, dan menjelaskan penyebab kesalahan.',
                'tema_agent' => 'CrewAI',
            ],
        ];

        foreach ($proyekDemo as $proyek) {
            Project::factory()->for($dosen)->create($proyek);
        }
    }
}
