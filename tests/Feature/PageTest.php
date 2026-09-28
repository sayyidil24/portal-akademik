<?php

namespace Tests\Feature;

use Tests\TestCase;

class PageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite(); // tidak perlu npm run build saat testing
    }

    public function test_tiga_halaman_dapat_diakses(): void
    {
        $this->get('/')->assertOk();
        $this->get('/profil-mahasiswa')->assertOk();
        $this->get('/ide-agent')->assertOk();
    }

    public function test_alert_selamat_datang_dari_parameter_user(): void
    {
        $this->get('/beranda?user=Andi')->assertOk()->assertSee('Selamat datang, Andi');
        $this->get('/beranda')->assertDontSee('Selamat datang');
    }

    public function test_user_di_url_di_escape(): void
    {
        $this->get('/beranda?user=<script>alert(1)</script>')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_mode_gelap_mengubah_class_html(): void
    {
        $this->get('/ide-agent?mode=dark')->assertSee('class="dark"', false);
        $this->get('/ide-agent')->assertDontSee('class="dark"', false);
    }

    public function test_form_ide_gagal_validasi(): void
    {
        $this->post('/ide-agent', ['nama' => '', 'ide' => 'pendek'])
            ->assertSessionHasErrors(['nama', 'ide']);
    }

    public function test_form_ide_berhasil(): void
    {
        $this->post('/ide-agent', ['nama' => 'Andi', 'ide' => 'Agen AI untuk review jurnal otomatis'])
            ->assertRedirect(route('ide-agent'))
            ->assertSessionHas('status');
    }
}
