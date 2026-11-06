<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_users_by_role(): void
    {
        $customer = $this->createUser('customer', 'test-customer', 'customer@example.com');
        $vendor = $this->createUser('vendor', 'test-vendor', 'vendor@example.com');

        $response = $this->withSession(['user_id' => 1, 'role' => 'admin'])
            ->get(route('admin.manage_users', ['role' => 'vendor']));

        $response->assertOk()
            ->assertSee($vendor->username)
            ->assertDontSee($customer->username);
    }

    public function test_admin_can_change_vendor_active_status(): void
    {
        $vendor = $this->createUser('vendor', 'test-vendor', 'vendor@example.com');

        $response = $this->withSession(['user_id' => 1, 'role' => 'admin'])
            ->put(route('admin.update_user', $vendor->user_id), [
                'is_active' => '0',
                'role_filter' => 'vendor',
            ]);

        $response->assertRedirect(route('admin.manage_users', ['role' => 'vendor']));
        $this->assertDatabaseHas('users', [
            'user_id' => $vendor->user_id,
            'role' => 'vendor',
            'is_active' => false,
        ]);
    }

    private function createUser(string $role, string $username, string $email): User
    {
        return User::create([
            'name' => ucfirst($role) . ' User',
            'username' => $username,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
            'is_active' => true,
        ]);
    }
}
