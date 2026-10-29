<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthUiTest extends TestCase
{
    public function test_login_page_is_rendered_without_role_selection(): void
    {
        $response = $this->get('/login');

        $response->assertOk()
            ->assertSee('Selamat datang kembali')
            ->assertSee('Alamat email')
            ->assertSee('Kata sandi')
            ->assertDontSee('Operator OPD')
            ->assertDontSee('Organisasi Perangkat Daerah');
    }

    public function test_dashboard_is_rendered_for_authenticated_users(): void
    {
        $user = new User([
            'name' => 'Andi Kurniawan',
            'email' => 'admin@siagakla.go.id',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Selamat datang, Andi')
            ->assertSee('Progres Periode 2026')
            ->assertSee('Progres per cluster')
            ->assertSee('Menunggu verifikasi')
            ->assertSee('Menu administrasi');
    }

    public function test_guests_are_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = new User([
            'name' => 'Andi Kurniawan',
            'email' => 'admin@siagakla.go.id',
            'password' => Hash::make('siagakla2026'),
        ]);

        $this->bindUserRepository($user);

        $response = $this->post('/login', [
            'email' => 'admin@siagakla.go.id',
            'password' => 'siagakla2026',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = new User([
            'name' => 'Andi Kurniawan',
            'email' => 'admin@siagakla.go.id',
            'password' => Hash::make('siagakla2026'),
        ]);

        $this->bindUserRepository($user);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@siagakla.go.id',
            'password' => 'kata-sandi-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = new User([
            'name' => 'Andi Kurniawan',
            'email' => 'admin@siagakla.go.id',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    private function bindUserRepository(?User $user): void
    {
        $this->app->instance(UserRepositoryInterface::class, new class($user) implements UserRepositoryInterface
        {
            public function __construct(private readonly ?User $user) {}

            public function findByEmail(string $email): ?User
            {
                return $this->user?->email === $email ? $this->user : null;
            }

            public function updatePassword(User $user, string $passwordHash): void
            {
                $user->forceFill(['password' => $passwordHash]);
            }

            public function paginateWithRoles(int $perPage = 10): LengthAwarePaginator
            {
                return new Paginator([], 0, $perPage);
            }

            public function queryWithRoles(): Builder
            {
                return User::query();
            }

            public function create(array $attributes): User
            {
                return new User($attributes);
            }

            public function update(User $user, array $attributes): User
            {
                $user->fill($attributes);

                return $user;
            }

            public function delete(User $user): void {}
        });
    }
}
