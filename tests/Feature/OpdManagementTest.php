<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpdManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_guests_are_redirected_to_login_from_opd_management(): void
    {
        $response = $this->get(route('admin.opds.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_users_without_permission_cannot_access_opd_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.opds.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_opd_list(): void
    {
        $opd = Opd::factory()->create([
            'kode_opd' => '1.01.01',
            'nama_opd' => 'Dinas Pendidikan',
            'singkatan' => 'Disdik',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.opds.index'));

        $response->assertOk()
            ->assertSee('Manajemen OPD')
            ->assertSee($opd->nama_opd)
            ->assertSee($opd->kode_opd);
    }

    public function test_admin_can_create_opd(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.opds.store'), [
            'kode_opd' => '1.09.01',
            'nama_opd' => 'Dinas Perhubungan',
            'singkatan' => 'Dishub',
            'deskripsi' => 'Mengelola transportasi daerah.',
            'alamat' => 'Jl. Merdeka No. 1',
            'email' => 'dishub@contoh.go.id',
            'telepon' => '021-1234567',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.opds.index'))
            ->assertSessionHas('status', 'OPD berhasil ditambahkan.');

        $this->assertDatabaseHas('opds', [
            'kode_opd' => '1.09.01',
            'nama_opd' => 'Dinas Perhubungan',
            'singkatan' => 'Dishub',
            'is_active' => true,
        ]);
    }

    public function test_opd_code_must_be_unique(): void
    {
        Opd::factory()->create(['kode_opd' => '1.01.01']);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.opds.create'))
            ->post(route('admin.opds.store'), [
                'kode_opd' => '1.01.01',
                'nama_opd' => 'Dinas Lain',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.opds.create'))
            ->assertSessionHasErrors('kode_opd');
    }

    public function test_admin_can_update_opd(): void
    {
        $opd = Opd::factory()->create([
            'kode_opd' => '1.02.01',
            'nama_opd' => 'Dinas Kesehatan',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.opds.update', $opd), [
            'kode_opd' => '1.02.02',
            'nama_opd' => 'Dinas Kesehatan Daerah',
            'singkatan' => 'Dinkes',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.opds.index'))
            ->assertSessionHas('status', 'Data OPD berhasil diperbarui.');

        $this->assertDatabaseHas('opds', [
            'id' => $opd->id,
            'kode_opd' => '1.02.02',
            'nama_opd' => 'Dinas Kesehatan Daerah',
        ]);
    }

    public function test_admin_can_delete_opd(): void
    {
        $opd = Opd::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.opds.destroy', $opd));

        $response->assertRedirect(route('admin.opds.index'))
            ->assertSessionHas('status', 'OPD berhasil dihapus.');

        $this->assertSoftDeleted('opds', ['id' => $opd->id]);
    }

    public function test_opd_with_users_cannot_be_deleted(): void
    {
        $opd = Opd::factory()->create();

        User::factory()->create(['opd_id' => $opd->id]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.opds.index'))
            ->delete(route('admin.opds.destroy', $opd));

        $response->assertRedirect(route('admin.opds.index'))
            ->assertSessionHasErrors('opd');

        $this->assertNotSoftDeleted('opds', ['id' => $opd->id]);
    }

    public function test_user_form_dropdown_lists_opds_from_database(): void
    {
        $opd = Opd::factory()->create([
            'nama_opd' => 'Dinas Pendidikan',
            'singkatan' => 'Disdik',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.create'));

        $response->assertOk()
            ->assertSee('Dinas Pendidikan')
            ->assertSee('Disdik');
    }
}
