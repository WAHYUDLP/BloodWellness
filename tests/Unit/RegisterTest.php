<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;  // Ini biar setiap test rollback otomatis

    /** @test */
    public function user_can_view_register_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm-password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('usersaccount', [  // Ubah sesuai nama tabel
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function registration_fails_if_passwords_do_not_match()
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm-password' => 'differentpassword',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('confirm-password');
        $this->assertDatabaseMissing('usersaccount', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function registration_fails_if_email_already_exists()
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm-password' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('email');
    }
}
