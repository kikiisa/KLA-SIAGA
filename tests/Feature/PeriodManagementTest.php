<?php

namespace Tests\Feature;

use App\Models\EvaluationPeriod;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PeriodManagementTest extends TestCase
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

    public function test_guests_are_redirected_to_login_from_period_management(): void
    {
        $response = $this->get(route('admin.periods.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_users_without_permission_cannot_access_period_management(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.periods.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_period_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.periods.index'));

        $response->assertOk()
            ->assertSee('Periode Evaluasi')
            ->assertSee('data-datatable', false)
            ->assertSee(route('admin.periods.data'), false);
    }

    public function test_admin_can_fetch_period_datatable_data(): void
    {
        $period = EvaluationPeriod::factory()->create([
            'code' => 'KLA-2026',
            'name' => 'Evaluasi Pemenuhan Indikator KLA Tahun 2026',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.periods.data'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.year', (string) $period->year);

        $this->assertStringContainsString($period->name, $response->json('data.0.name'));
    }

    public function test_period_datatable_search_does_not_return_an_error(): void
    {
        EvaluationPeriod::factory()->create(['name' => 'Evaluasi KLA 2026']);
        EvaluationPeriod::factory()->create(['name' => 'Evaluasi KLA 2027']);
        EvaluationPeriod::factory()->active()->create(['name' => 'Klasemen Aktif']);

        $response = $this->actingAs($this->admin)->getJson(route('admin.periods.data', $this->dataTableQuery([
            ['data' => 'name', 'name' => 'name', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'year', 'name' => 'year', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'start_date', 'name' => 'start_date', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'status_label', 'name' => 'status_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'action', 'name' => 'action', 'searchable' => 'false', 'orderable' => 'false'],
        ], '2026')));

        $response->assertOk();

        $this->assertArrayNotHasKey('error', $response->json());

        $response = $this->actingAs($this->admin)->getJson(route('admin.periods.data', $this->dataTableQuery([
            ['data' => 'name', 'name' => 'name', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'year', 'name' => 'year', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'start_date', 'name' => 'start_date', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'status_label', 'name' => 'status_label', 'searchable' => 'true', 'orderable' => 'false'],
            ['data' => 'action', 'name' => 'action', 'searchable' => 'false', 'orderable' => 'false'],
        ], 'Aktif')));

        $this->assertArrayNotHasKey('error', $response->json());
        $this->assertStringContainsString('Klasemen Aktif', $response->json('data.0.name'));
    }

    public function test_admin_can_create_period(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.periods.store'), [
            'code' => 'KLA-2026',
            'name' => 'Evaluasi Pemenuhan Indikator KLA Tahun 2026',
            'year' => '2026',
            'description' => 'Evaluasi tahunan KLA.',
            'start_date' => '2026-01-01',
            'end_date' => '2026-11-30',
        ]);

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHas('status', 'Periode evaluasi berhasil ditambahkan.');

        $this->assertDatabaseHas('evaluation_periods', [
            'code' => 'KLA-2026',
            'name' => 'Evaluasi Pemenuhan Indikator KLA Tahun 2026',
            'year' => 2026,
            'start_date' => '2026-01-01',
            'end_date' => '2026-11-30',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_period_code_must_be_unique(): void
    {
        EvaluationPeriod::factory()->create(['code' => 'KLA-2026']);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.periods.create'))
            ->post(route('admin.periods.store'), [
                'code' => 'KLA-2026',
                'name' => 'Periode Lain',
                'year' => '2027',
                'start_date' => '2027-01-01',
                'end_date' => '2027-11-30',
            ]);

        $response->assertRedirect(route('admin.periods.create'))
            ->assertSessionHasErrors('code');
    }

    public function test_collection_deadline_must_be_after_start_date(): void
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.periods.create'))
            ->post(route('admin.periods.store'), [
                'code' => 'KLA-2026',
                'name' => 'Periode Terbalik',
                'year' => '2026',
                'start_date' => '2026-11-30',
                'end_date' => '2026-01-01',
            ]);

        $response->assertRedirect(route('admin.periods.create'))
            ->assertSessionHasErrors('end_date');
    }

    public function test_admin_can_update_period(): void
    {
        $period = EvaluationPeriod::factory()->create(['code' => 'KLA-2026']);

        $response = $this->actingAs($this->admin)->put(route('admin.periods.update', $period), [
            'code' => 'KLA-2027',
            'name' => 'Evaluasi Pemenuhan Indikator KLA Tahun 2027',
            'year' => '2027',
            'start_date' => '2027-01-01',
            'end_date' => '2027-11-30',
        ]);

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHas('status', 'Periode evaluasi berhasil diperbarui.');

        $this->assertDatabaseHas('evaluation_periods', [
            'id' => $period->id,
            'code' => 'KLA-2027',
            'name' => 'Evaluasi Pemenuhan Indikator KLA Tahun 2027',
        ]);
    }

    public function test_admin_can_delete_draft_period(): void
    {
        $period = EvaluationPeriod::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.periods.destroy', $period));

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHas('status', 'Periode evaluasi berhasil dihapus.');

        $this->assertSoftDeleted('evaluation_periods', ['id' => $period->id]);
    }

    public function test_active_period_cannot_be_deleted(): void
    {
        $period = EvaluationPeriod::factory()->active()->create();

        $response = $this->actingAs($this->admin)
            ->from(route('admin.periods.index'))
            ->delete(route('admin.periods.destroy', $period));

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHasErrors('period');

        $this->assertNotSoftDeleted('evaluation_periods', ['id' => $period->id]);
    }

    public function test_period_with_clusters_cannot_be_deleted(): void
    {
        $period = EvaluationPeriod::factory()->create(['status' => 'draft']);

        DB::table('clusters')->insert([
            'period_id' => $period->id,
            'code' => 'C-01',
            'name' => 'Hak Sipil dan Kebebasan',
            'status' => 'active',
            'created_by' => $this->admin->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.periods.index'))
            ->delete(route('admin.periods.destroy', $period));

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHasErrors('period');

        $this->assertNotSoftDeleted('evaluation_periods', ['id' => $period->id]);
    }

    public function test_admin_can_activate_draft_period(): void
    {
        $period = EvaluationPeriod::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.periods.status', $period), ['status' => 'active']);

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHas('status', 'Periode evaluasi berhasil diaktifkan.');

        $this->assertDatabaseHas('evaluation_periods', [
            'id' => $period->id,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_close_active_period_and_archive_closed_period(): void
    {
        $period = EvaluationPeriod::factory()->active()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.periods.status', $period), ['status' => 'closed']);

        $response->assertRedirect(route('admin.periods.index'));

        $this->assertDatabaseHas('evaluation_periods', ['id' => $period->id, 'status' => 'closed']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.periods.status', $period), ['status' => 'archived']);

        $response->assertRedirect(route('admin.periods.index'));

        $this->assertDatabaseHas('evaluation_periods', ['id' => $period->id, 'status' => 'archived']);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $period = EvaluationPeriod::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.periods.index'))
            ->put(route('admin.periods.status', $period), ['status' => 'closed']);

        $response->assertRedirect(route('admin.periods.index'))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('evaluation_periods', ['id' => $period->id, 'status' => 'draft']);
    }

    public function test_operator_cannot_change_period_status(): void
    {
        $operator = User::factory()->create();
        $operator->assignRole('operator_opd');

        $period = EvaluationPeriod::factory()->create(['status' => 'draft']);

        $response = $this->actingAs($operator)
            ->put(route('admin.periods.status', $period), ['status' => 'active']);

        $response->assertForbidden();

        $this->assertDatabaseHas('evaluation_periods', ['id' => $period->id, 'status' => 'draft']);
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
