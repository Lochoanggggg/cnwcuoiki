<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_guest_can_register_and_is_logged_in(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Nguyen Van A',
            'email' => 'new-customer@example.com',
            'phone' => '0901234567',
            'address' => 'Ha Noi',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new-customer@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Nguyen Van B',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_category_management_requires_store_owner_access(): void
    {
        $this->get(route('categories.index'))
            ->assertRedirect(route('login'));

        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('categories.index'))
            ->assertForbidden();
    }

    public function test_store_owner_can_access_category_management_from_the_shared_interface(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        Category::create([
            'name' => 'Áo',
            'slug' => 'ao',
            'description' => 'Danh mục áo.',
            'is_active' => true,
        ]);

        $this->actingAs($owner)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Quản lý danh mục')
            ->assertSee(route('categories.create'));
    }
}
