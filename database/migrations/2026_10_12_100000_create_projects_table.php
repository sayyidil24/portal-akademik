<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel projects: rancangan ide proposal Agentic AI milik user.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Relasi ke users.id; jika user dihapus, seluruh project-nya ikut terhapus
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('tema_agent')->default('Ollama');

            // Disimpan terenkripsi (cast 'encrypted' di model), hasil enkripsi panjang sehingga memakai TEXT
            $table->text('api_key_secure');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
