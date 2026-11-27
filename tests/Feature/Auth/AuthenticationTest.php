<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = $this->createUserWithRole('petugas');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'petugas',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = $this->createUserWithRole('petugas');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'role' => 'petugas',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_a_different_role(): void
    {
        $user = $this->createUserWithRole('petugas');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = $this->createUserWithRole('petugas');

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    private function createUserWithRole(string $slug): User
    {
        $role = Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => ucfirst($slug)]
        );

        return User::factory()->create(['role_id' => $role->id]);
    }
}
