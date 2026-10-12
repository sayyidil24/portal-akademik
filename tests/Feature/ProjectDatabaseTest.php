<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectDatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite(); // tidak perlu npm run build saat testing
    }

    public function test_dashboard_mewajibkan_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_hanya_menampilkan_project_milik_user_login(): void
    {
        $saya = User::factory()->create();
        $lain = User::factory()->create();

        Project::factory()->for($saya)->create(['judul' => 'Proyek Milik Saya']);
        Project::factory()->for($lain)->create(['judul' => 'Proyek Milik Orang Lain']);

        $this->actingAs($saya)->get('/dashboard')
            ->assertOk()
            ->assertSee('Proyek Milik Saya')
            ->assertDontSee('Proyek Milik Orang Lain');
    }

    public function test_project_diurutkan_dari_yang_terbaru(): void
    {
        $user = User::factory()->create();

        Project::factory()->for($user)->create(['judul' => 'Proyek Lama', 'created_at' => now()->subDays(2)]);
        Project::factory()->for($user)->create(['judul' => 'Proyek Baru', 'created_at' => now()]);

        $this->actingAs($user)->get('/dashboard')
            ->assertSeeInOrder(['Proyek Baru', 'Proyek Lama']);
    }

    public function test_default_tema_agent_dan_deskripsi_nullable_di_level_database(): void
    {
        $user = User::factory()->create();

        DB::table('projects')->insert([
            'user_id' => $user->id,
            'judul' => 'Tanpa Deskripsi',
            'api_key_secure' => 'abc',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('Ollama', DB::table('projects')->value('tema_agent'));
        $this->assertNull(DB::table('projects')->value('deskripsi'));
    }

    public function test_menghapus_user_ikut_menghapus_project_miliknya(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(2)->for($user)->create();

        $user->delete();

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_fillable_melindungi_user_id_dari_mass_assignment(): void
    {
        $project = new Project;

        $this->assertTrue($project->isFillable('judul'));
        $this->assertFalse($project->isFillable('user_id'));
    }

    public function test_api_key_terenkripsi_di_database_dan_tersembunyi_dari_array(): void
    {
        $project = Project::factory()->create(['api_key_secure' => 'sk-rahasia-123']);

        $mentah = DB::table('projects')->where('id', $project->id)->value('api_key_secure');

        $this->assertNotSame('sk-rahasia-123', $mentah);
        $this->assertSame('sk-rahasia-123', $project->fresh()->api_key_secure);
        $this->assertArrayNotHasKey('api_key_secure', $project->fresh()->toArray());
    }

    public function test_seeder_membuat_15_mahasiswa_akun_demo_dan_aman_dijalankan_ulang(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['email' => 'dosenpbkk@its.ac.id']);
        $this->assertSame(15, User::where('email', 'like', '%@student.its.ac.id')->count());
        $this->assertSame(16, User::count());
        $this->assertSame(33, Project::count()); // 15 x 2 project + 3 project demo

        $this->seed(); // dijalankan lagi: tidak boleh error duplikasi

        $this->assertSame(16, User::count());
        $this->assertSame(33, Project::count());
    }
}
