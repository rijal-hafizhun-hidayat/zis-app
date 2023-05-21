<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_middleware_after_login(): void
    {
        $response = $this->withSession(
            [
                'isLogin' => true,
                'role' => 1
            ]
            )->get('/dashboard');
 
        $response->assertStatus(200);
    }

    public function test_post_login(): void{

        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);
        
        $this->post('/login', [
            'username' => $user->username,
            'password' => 'admin'
        ]);

        $this->assertAuthenticated();
    }

    public function test_if_not_login(): void{
        $response = $this->get('/dashboard');

        $response->assertStatus(302);
    }

    public function test_validation_if_no_credential(): void{
        $response = $this->post('/login', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertInvalid(['username', 'password']);
    }

    public function test_if_wrong_username_and_password(): void{

        User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $response = $this->post('/login', [
            'username' => 'ascwaa',
            'password' => 'ascascas'
        ]);

        $response->assertStatus(404);
    }

    public function test_if_role_not_admin(): void {
        $this->withSession([
            'isLogin' => true,
            'role' => 0
        ])->get('/akun')->assertStatus(302);
    }

    public function test_if_admin(): void{
        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ])->get('/akun')->assertStatus(200);
    }

    public function test_logout(): void
    {
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $this->actingAs($user);
        
        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);

        $this->post('/logout');
        $this->assertGuest();
        
    }
}
