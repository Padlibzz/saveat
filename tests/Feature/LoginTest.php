<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_login_dengan_kredensial_yang_benar()
    {
       $user = User::create([
        'nama'        => 'Test User',
        'email'       => 'test@saveat.com',
        'username'    => 'testuser',
        'password'    => bcrypt('password123'),
        'peran'       => 'konsumen', 
        'status'      => 'aktif',    
        'no_telphone' => '08123456789' 
    ]);

        $response = $this->postJson('/api/login', [
            'login_identifier' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token']);
    }
}