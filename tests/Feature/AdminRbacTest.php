<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RolePermissionSeeder::class);
    }

    private function buatUser(?string $peran = null): User
    {
        $user = User::factory()->create();

        if ($peran) {
            $user->assignRole($peran);
        }

        return $user;
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_user_tanpa_peran_ditolak_403(): void
    {
        $mahasiswa = $this->buatUser();

        $this->actingAs($mahasiswa)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($mahasiswa)->get('/admin/projects')->assertForbidden();
    }

    public function test_admin_dan_staf_dapat_membuka_panel(): void
    {
        foreach (['Admin', 'Staf'] as $peran) {
            $this->actingAs($this->buatUser($peran))
                ->get('/admin/dashboard')
                ->assertOk();

            $this->actingAs($this->buatUser($peran))
                ->get('/admin/projects')
                ->assertOk();
        }
    }

    public function test_dashboard_admin_menampilkan_widget_statistik(): void
    {
        $admin = $this->buatUser('Admin');
        Project::factory()->count(3)->for($admin)->create();

        $this->actingAs($admin)->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Total User')
            ->assertSee('Total Project')
            ->assertSee('Pengelola Panel')
            ->assertSee('Project 7 Hari Terakhir');
    }

    public function test_staf_tidak_bisa_menghapus_dan_tombol_hapus_tersembunyi(): void
    {
        $staf = $this->buatUser('Staf');
        $project = Project::factory()->create();
        $urlHapus = route('admin.projects.destroy', $project);

        $this->actingAs($staf)->get('/admin/projects')
            ->assertOk()
            ->assertDontSee($urlHapus, false);

        $this->actingAs($staf)->delete($urlHapus)->assertForbidden();

        $this->assertModelExists($project);
    }

    public function test_admin_melihat_tombol_hapus_dan_bisa_menghapus(): void
    {
        $admin = $this->buatUser('Admin');
        $project = Project::factory()->create();
        $urlHapus = route('admin.projects.destroy', $project);

        $this->actingAs($admin)->get('/admin/projects')
            ->assertOk()
            ->assertSee($urlHapus, false);

        $this->actingAs($admin)->delete($urlHapus)->assertRedirect();

        $this->assertModelMissing($project);
    }

    public function test_pencarian_when_dan_pagination_membawa_parameter(): void
    {
        $admin = $this->buatUser('Admin');
        Project::factory()->count(12)->create(['judul' => 'Agen Zebra Unik']);
        Project::factory()->count(3)->create(['judul' => 'Proyek Lain']);

        $this->actingAs($admin)->get('/admin/projects?search=Zebra')
            ->assertOk()
            ->assertSee('Agen Zebra Unik')
            ->assertDontSee('Proyek Lain')
            ->assertSee('search=Zebra', false); // tautan halaman 2 tetap membawa kata kunci
    }

    public function test_filter_tema_agent(): void
    {
        $admin = $this->buatUser('Admin');
        Project::factory()->create(['judul' => 'Proyek Satu', 'tema_agent' => 'Ollama']);
        Project::factory()->create(['judul' => 'Proyek Dua', 'tema_agent' => 'CrewAI']);

        $this->actingAs($admin)->get('/admin/projects?tema_agent=CrewAI')
            ->assertOk()
            ->assertSee('Proyek Dua')
            ->assertDontSee('Proyek Satu');
    }

    public function test_admin_dan_staf_dialihkan_dari_dashboard_mahasiswa(): void
    {
        $this->actingAs($this->buatUser('Admin'))->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($this->buatUser())->get('/dashboard')->assertOk();
    }

    public function test_login_instan_ditolak_di_luar_lingkungan_local(): void
    {
        $this->seed();

        $this->post('/demo-login/admin')->assertNotFound();
        $this->assertGuest();
    }

    public function test_login_instan_berfungsi_di_lingkungan_local(): void
    {
        $this->seed();
        $this->app->detectEnvironment(fn () => 'local');

        $this->get('/login')->assertSee('Masuk sebagai Admin');

        $this->post('/demo-login/staf')->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->hasRole('Staf'));
    }
}
