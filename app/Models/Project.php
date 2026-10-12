<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    /**
     * Proteksi mass-assignment: hanya kolom ini yang boleh diisi massal.
     * user_id sengaja tidak dimasukkan agar pemilik project tidak bisa dipalsukan lewat input form.
     * Isi user_id lewat relasi atau factory, bukan dari request.
     *
     * @var list<string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'tema_agent',
        'api_key_secure',
    ];

    /**
     * Kolom yang tidak ikut saat model diubah menjadi array atau JSON.
     *
     * @var list<string>
     */
    protected $hidden = [
        'api_key_secure',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Dienkripsi otomatis saat disimpan dan didekripsi saat dibaca (memakai APP_KEY)
            'api_key_secure' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
