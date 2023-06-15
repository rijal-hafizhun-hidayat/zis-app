<?php

namespace Tests\Feature;

use App\Models\Infaq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InfaqTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_infaq_page(): void
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
        ])->get('/infaq');

        $response->assertOk();
    }

    public function test_get_infaq_page_not_authenticade(): void{
        $response = $this->get(route('infaq'));

        $response->assertFound();
    }

    public function test_store_infaq(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('infaq.store'), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'nomor_hp' => 628139378414,
            'metode_pembayaran' => 'Rekening',
            'nominal' => 34000,
            'bulan' => 5,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertOk();
    }

    public function test_validation_store_infaq(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('infaq.store'), [
            'nama_donatur' => '',
            'nomor_hp' => null,
            'metode_pembayaran' => 'Rekening',
            'nominal' => 'rijal hafizhun hidayat',
            'bulan' => 5,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_delete_infaq_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $infaq = Infaq::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->delete(route('infaq.destroy', ['id' => $infaq->id]));

        $response->assertOk();
    }

    public function test_put_infaq_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $infaq = Infaq::factory()->create();

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => 1
        ])->put(route('infaq.update', ['id' => $infaq->id]), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'nomor_hp' => 628139378414,
            'metode_pembayaran' => 'Rekening',
            'nominal' => 34000,
            'bulan' => 5,
            'bukti_pembayaran' => $infaq->bukti_pembayaran,
            'confirmed' => 0
        ]);

        $this->assertDatabaseHas('infaq', [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'nomor_hp' => 628139378414
        ]);
    }

    public function test_validation_put_infaq_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $infaq = Infaq::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('infaq.update', ['id' => $infaq->id]), [
            'nama_donatur' => '',
            'nomor_hp' => null,
            'metode_pembayaran' => 'Rekening',
            'nominal' => 'rijal hafizhun hidayat',
            'bulan' => 5,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_confirmed_pembayaran_infaq(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $infaq = Infaq::factory()->create();

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('infaq.confirmed', ['id' => $infaq->id]), [
            'confirmed' => 1
        ]);

        $this->assertDatabaseHas('infaq', [
            'confirmed' => 1
        ]);
    }

    public function test_cetak_laporan_infaq(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('infaq.laporan'));

        $response->assertOk();
    }
}
