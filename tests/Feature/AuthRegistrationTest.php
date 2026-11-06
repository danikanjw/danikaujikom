<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_with_the_current_users_schema(): void
    {
        $city = City::create(['name' => 'Bandung']);

        $response = $this->post(route('register.post'), $this->registrationData());

        $user = User::where('username', 'new-customer')->firstOrFail();

        $response->assertRedirect('/product');
        $response->assertSessionHas('user_id', $user->user_id);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'name' => 'New Customer',
            'city_id' => $city->id,
            'role' => 'customer',
            'is_active' => true,
        ]);
        $this->assertNotSame('secret123', $user->password);
    }

    public function test_vendor_registration_creates_a_pending_inactive_vendor(): void
    {
        City::create(['name' => 'Bandung']);

        $response = $this->post(route('vendor.register.post'), $this->registrationData([
            'username' => 'new-vendor',
            'name' => 'New Vendor',
            'email' => 'vendor@example.com',
        ]));

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'username' => 'new-vendor',
            'role' => 'vendor',
            'vendor_status' => 'pending',
            'is_active' => false,
        ]);
    }

    public function test_inactive_vendor_cannot_log_in_until_approved(): void
    {
        City::create(['name' => 'Bandung']);
        $this->post(route('vendor.register.post'), $this->registrationData([
            'username' => 'new-vendor',
            'name' => 'New Vendor',
            'email' => 'vendor@example.com',
        ]));

        $response = $this->post(route('login.post'), [
            'username' => 'new-vendor',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('login');

        $user = User::where('username', 'new-vendor')->firstOrFail();
        $user->update(['vendor_status' => 'approved', 'is_active' => true]);

        $response = $this->post(route('login.post'), [
            'username' => 'new-vendor',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/vendor/dashboard');
        $response->assertSessionHas('user_id', $user->user_id);
        $this->assertAuthenticatedAs($user);
    }

    private function registrationData(array $overrides = []): array
    {
        return array_merge([
            'username' => 'new-customer',
            'name' => 'New Customer',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'email' => 'customer@example.com',
            'dob' => '1990-01-01',
            'gender' => 'female',
            'address' => 'Jl. Contoh',
            'city' => 'Bandung',
            'contact' => '08123456789',
        ], $overrides);
    }
}
