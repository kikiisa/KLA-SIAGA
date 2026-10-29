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
        $response = $this->actingAs($this->admin)->get(route('admin.opds.index'));

        $response->assertOk()
            ->assertSee('Manajemen OPD')
            ->assertSee('data-datatable', false)
            ->assertSee(route('admin.opds.data'), false);
    }

    public function test_admin_can_fetch_opd_datatable_data(): void
    {
        $opd = Opd::factory()->create([
            'kode_opd' => '1.01.01',
            'nama_opd' => 'Dinas Pendidikan',
            'singkatan' => 'Disdik',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.opds.data'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.kode_opd', '1.01.01');

        $this->assertStringContainsString($opd->nama_opd, $response->json('data.0.nama_opd'));
    }

    public function test_admin_can_fetch_user_datatable_data(): void
    {
        $opd = Opd::factory()->create(['nama_opd' => 'Dinas Pendidikan']);

        $user = User::factory()->create([
            'name' => 'Operator Pendidikan',
            'email' => 'operator@contoh.go.id',
            'opd_id' => $opd->id,
        ]);
        $user->assignRole('operator_opd');

        $response = $this->actingAs($this->admin)->getJson(route('admin.users.data'));

        $response->assertOk();

        $row = collect($response->json('data'))->firstWhere('email', 'operator@contoh.go.id');

        $this->assertNotNull($row);
        $this->assertStringContainsString($user->name, $row['name']);
        $this->assertStringContainsString('Operator OPD', $row['roles_label']);
    }

    public function test_opd_datatable_search_does_not_return_an_error(): void
    {
        Opd::factory()->create([
            'kode_opd' => '1.01.01',
            'nama_opd' => 'Dinas Pendidikan',
        ]);
        Opd::factory()->create(['nama_opd' => 'Dinas Kesehatan']);

        $response = $this->actingAs($this->admin)->getJson(route('admin.opds.data', $this->dataTableQuery([
            ['data' => 'nama_opd', 'name' => 'nama_opd', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'kode_opd', 'name' => 'kode_opd', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'kontak', 'name' => 'kontak', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'users_count', 'name' => 'users_count', 'searchable' => 'false', 'orderable' => 'true'],
            ['data' => 'status_label', 'name' => 'status_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'action', 'name' => 'action', 'searchable' => 'false', 'orderable' => 'false'],
        ], 'Pendidikan')));

        $response->assertOk()->assertJsonCount(1, 'data');

        $this->assertArrayNotHasKey('error', $response->json());
        $this->assertStringContainsString('Dinas Pendidikan', $response->json('data.0.nama_opd'));
    }

    public function test_user_datatable_search_does_not_return_an_error(): void
    {
        $opd = Opd::factory()->create(['nama_opd' => 'Dinas Pendidikan']);

        $user = User::factory()->create([
            'name' => 'Operator Pendidikan',
            'email' => 'operator@contoh.go.id',
            'opd_id' => $opd->id,
        ]);
        $user->assignRole('operator_opd');

        $response = $this->actingAs($this->admin)->getJson(route('admin.users.data', $this->dataTableQuery([
            ['data' => 'name', 'name' => 'name', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'email', 'name' => 'email', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'opd_label', 'name' => 'opd_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'roles_label', 'name' => 'roles_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'status_label', 'name' => 'status_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'action', 'name' => 'action', 'searchable' => 'false', 'orderable' => 'false'],
        ], 'Operator')));

        $response->assertOk();

        $row = collect($response->json('data'))->firstWhere('email', 'operator@contoh.go.id');

        $this->assertArrayNotHasKey('error', $response->json());
        $this->assertNotNull($row);
        $this->assertStringContainsString($user->name, $row['name']);
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

    public function test_post_result_renders_sweet_alert_payload(): void
    {
        $response = $this->actingAs($this->admin)
            ->followingRedirects()
            ->post(route('admin.opds.store'), [
                'kode_opd' => '1.10.01',
                'nama_opd' => 'Dinas Pekerjaan Umum',
                'is_active' => '1',
            ]);

        $response->assertOk()
            ->assertSee('data-sweet-alert', false)
            ->assertSee('data-icon="success"', false)
            ->assertSee('OPD berhasil ditambahkan.');
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

    /**
     * Build a DataTables request payload for tests.
     *
     * @param  list<array{data: string, name: string, searchable: string, orderable: string}>  $columns
     * @return array<string, mixed>
     */
    private function dataTableQuery(array $columns, string $searchValue): array
    {
        return [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'search' => ['value' => $searchValue, 'regex' => 'false'],
            'order' => [['column' => 0, 'dir' => 'asc']],
            'columns' => array_map(
                fn (array $column): array => $column + ['search' => ['value' => '', 'regex' => 'false']],
                $columns,
            ),
        ];
    }
}
