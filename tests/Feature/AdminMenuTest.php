<?php

namespace Tests\Feature;

use App\Models\User;
use Mockery;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    public function test_admin_menus_are_visible_for_authorized_users(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 1;
        $user->name = 'Admin KLA';
        $user->shouldReceive('can')->with('user.view')->andReturn(true);
        $user->shouldReceive('can')->with('role.view')->andReturn(true);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertSee('Manajemen Pengguna')
            ->assertSee('Role & Permission');
    }

    public function test_admin_menus_are_hidden_for_unauthorized_users(): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->id = 2;
        $user->name = 'Operator OPD';
        $user->shouldReceive('can')->andReturn(false);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertDontSee('Manajemen Pengguna')
            ->assertDontSee('Role & Permission');
    }
}
