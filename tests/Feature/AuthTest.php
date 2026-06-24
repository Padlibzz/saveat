<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_shows_correct_error_for_username()
    {
        $user = User::factory()->create(['username' => 'testuser', 'password' => bcrypt('password')]);

        $response = $this->withSession(['_token' => 'test-token'])->post('/auth/login', [
            '_token' => 'test-token',
            'login' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['login' => 'Username atau password salah.']);
    }

    public function test_login_shows_correct_error_for_email()
    {
        $user = User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password')]);

        $response = $this->withSession(['_token' => 'test-token'])->post('/auth/login', [
            '_token' => 'test-token',
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['login' => 'Email atau password salah.']);
    }

    public function test_register_shows_error_if_username_taken()
    {
        User::factory()->create(['username' => 'existinguser']);

        $response = $this->withSession(['_token' => 'test-token'])->post('/auth/register', [
            '_token' => 'test-token',
            'name' => 'Test User',
            'username' => 'existinguser',
            'email' => 'new@test.com',
            'password' => 'password123',
            'no_telphone' => '08123456789',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['username' => 'Username sudah digunakan.']);
    }

    public function test_register_shows_error_if_email_taken()
    {
        User::factory()->create(['email' => 'existing@test.com']);

        $response = $this->withSession(['_token' => 'test-token'])->post('/auth/register', [
            '_token' => 'test-token',
            'name' => 'Test User',
            'username' => 'newuser',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'no_telphone' => '08123456789',
        ]);

        $response->assertStatus(302)->assertSessionHasErrors(['email' => 'Email sudah digunakan.']);
    }
}
