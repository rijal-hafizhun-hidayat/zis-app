<?php

namespace Tests\Feature;

use App\Models\Sha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShaTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_sha_page(): void
    {
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get(route('sha'));

        $response->assertOk();
    }

    public function test_get_sha_page_if_not_authenticate(): void{
        $response = $this->get(route('sha'));

        $response->assertFound();
    }

    public function test_store_sha(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('sha.store'), [
            'nama' => 'beras',
            'harga' => 45000
        ]);

        $response->assertOk();

    }

    public function test_store_validation_sha_page(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('sha.store'), [
            'nama' => '',
            'harga' => 'rijal hafizhun hidayat'
        ]);

        $response->assertInvalid();
    }

    public function test_delete_sha_data_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $sha = Sha::factory()->create();

        //dd($sha);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->delete(route('sha.destroy', ['id' => $sha->id]));

        $response->assertOk();
    }

    public function test_put_sha_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $sha = Sha::factory()->create();

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('sha.update', ['id' => $sha->id]), [
            'nama' => 'Cash',
            'harga' => 65000
        ]);

        $this->assertDatabaseHas('sha', [
            'nama' => 'Cash',
        ]);
    }

    public function test_validation_put_sha_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $sha = Sha::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('sha.update', ['id' => $sha->id]), [
            'nama' => '',
            'harga' => ''
        ]);

        $response->assertInvalid();
    }
}
