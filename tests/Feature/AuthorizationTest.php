<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_access_dashboard_but_not_reports(): void
    {
        $user = $this->createUserWithRole('petugas');

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/reports')->assertForbidden();
    }

    public function test_admin_can_access_reports(): void
    {
        $user = $this->createUserWithRole('admin');

        $this->actingAs($user)->get('/reports')->assertOk();
    }

    public function test_authenticated_user_without_a_role_is_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
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
