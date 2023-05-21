<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AkunTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_page_akun(): void
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
            'role' => $user->role
        ]);

        $response = $this->get('/akun');

        $response->assertStatus(200);
    }

    public function test_page_get_page_create_akun(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $this->actingAs($user);
        
        $this->withSession([
            'isLogin' => true,
            'role' => $user->role
        ]);

        $response = $this->get('/akun/add');

        $response->assertStatus(200);
    }

    public function test_store_akun(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $this->actingAs($user);
        
        $this->withSession([
            'isLogin' => true,
            'role' => $user->role
        ]);

        $this->post('/akun', [
            'name' => 'rijal',
            'username' => 'bebs21',
            'role' => 0,
            'password' => 'cirebon123'
        ])->assertStatus(201);
    }

    public function test_validation_create_akun(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ]);

        $response = $this->post('/akun', [
            'name' => 1242,
            'username' => '',
            'role' => 'admin',
            'password' => 'passwordnya1'
        ]);
        
        $response->assertInvalid(['name', 'role']);
    }

    public function test_destroy_akun(): void{
        $user = User::create(
        [
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);

        $response = $this->delete(route('akun.destroy', ['id' => 1]));

        $response->assertStatus(200);
    }

    public function test_update_akun_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 0
        ]);

        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ])->get(route('akun'));

        $response = $this->put(route('akun.update', ['id' => $user->id]), [
            'name' => 'rijal',
            'username' => 'hafizhun',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW', //default pass: admin
            'role' => 1
        ]);

        $response->assertOk();
    }

    public function test_validation_if_no_value_in_update_akun_page(): void{
        $user = User::create([
            'name' => 'faisal',
            'username' => 'nurhidayat',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $this->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get(route('akun'));

        $response = $this->put(route('akun.update', ['id' => $user->id]), [
            'name' => '',
            'username' => '',
            'password' => $user->password,
            'role' => $user->role
        ]);

        $response->assertInvalid(['name', 'username']);
    }
}
