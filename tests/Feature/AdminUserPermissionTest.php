<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users(): void
    {
        $admin = $this->userWithRole('admin');
        $staff = $this->userWithRole('staff');

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertOk();
        $response->assertSee($staff->name);
        $response->assertSee($staff->email);
    }

    public function test_admin_can_create_users(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->post(route('users.store'), [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => 'password',
                'role' => 'manager',
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);
    }

    public function test_admin_can_update_users(): void
    {
        $admin = $this->userWithRole('admin');
        $user = $this->userWithRole('staff');

        $this->actingAs($admin)
            ->put(route('users.update', $user), [
                'name' => 'Updated User',
                'email' => 'updated-user@example.com',
                'role' => 'manager',
            ])
            ->assertRedirect(route('users.edit', $user->id));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'email' => 'updated-user@example.com',
            'role' => 'manager',
        ]);
    }

    public function test_admin_can_delete_users(): void
    {
        $admin = $this->userWithRole('admin');
        $user = $this->userWithRole('staff');

        $this->actingAs($admin)
            ->delete(route('users.destroy', $user))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_manager_and_staff_cannot_access_user_management(): void
    {
        foreach (['manager', 'staff'] as $role) {
            $this->actingAs($this->userWithRole($role))
                ->get(route('users.index'))
                ->assertForbidden();
        }
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }
}
