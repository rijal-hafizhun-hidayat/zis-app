<?php

namespace Tests\Feature;

use App\Models\Sha;
use App\Models\User;
use App\Models\Zakat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ZakatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_zakat_page(): void
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
        ])->get(route('zakat'));

        $response->assertOk();
    }

    public function test_get_zakat_page_if_not_authenticate(): void{
        $response = $this->get(route('zakat'));

        $response->assertFound();
    }

    public function test_store_zakat(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('zakat.store'), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'jenis_zakat' => 'Zakat Fitrah',
            'nomor_hp' => 628139378414,
            'sha_id' => 1,
            'berat_beras' => 4.5,
            'jumlah' => 1,
            'nominal' => '',
            'bulan' => 3,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertOk();
    }

    public function test_validation_store_zakat(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('zakat.store'), [
            'nama_donatur' => '',
            'jenis_zakat' => '',
            'nomor_hp' => 628139378414,
            'sha_id' => 1,
            'berat_beras' => 4.5,
            'jumlah' => 1,
            'nominal' => '',
            'bulan' => 3,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_delete_zakat_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $zakat = Zakat::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->delete(route('zakat.destroy', ['id' => $zakat->id]));

        $response->assertOk();
    }

    public function test_put_zakat_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $zakat = Zakat::factory()->create();

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('zakat.update', ['id' => $zakat->id]), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'jenis_zakat' => 'Zakat Maal',
            'nomor_hp' => 628139378414,
            'sha_id' => 2,
            'berat_beras' => '',
            'jumlah' => '',
            'nominal' => 245000,
            'bulan' => 3,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $this->assertDatabaseHas('zakat', [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'jenis_zakat' => 'Zakat Maal'
        ]);
    }

    public function test_validation_update_zakat(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $zakat = Zakat::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('zakat.update', ['id' => $zakat->id]), [
            'nama_donatur' => '',
            'jenis_zakat' => 10000,
            'nomor_hp' => 628139378414,
            'sha_id' => 2,
            'berat_beras' => '',
            'jumlah' => '',
            'nominal' => 245000,
            'bulan' => 3,
            'bukti_pembayaran' => '',
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_validation_upload_image_in_store_zakat(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $zakat = Zakat::factory()->make();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('zakat.store'), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'jenis_zakat' => 'Zakat Fitrah',
            'nomor_hp' => 628139378414,
            'sha_id' => 1,
            'berat_beras' => 4.5,
            'jumlah' => 1,
            'nominal' => '',
            'bulan' => 3,
            'bukti_pembayaran' => $zakat->bukti_pembayaran,
            'confirmed' => 0
        ]);

        $response->assertValid();
    }

    public function test_validation_upload_image_in_update_zakat(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $zakat = Zakat::factory()->create();
        $zakatUpdate = Zakat::factory()->make();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('zakat.update', ['id' => $zakat->id]), [
            'nama_donatur' => 'kevin maulana',
            'jenis_zakat' => 'Zakat Maal',
            'nomor_hp' => 628139378414,
            'sha_id' => 2,
            'berat_beras' => '',
            'jumlah' => '',
            'nominal' => 245000,
            'bulan' => 3,
            'bukti_pembayaran' => $zakatUpdate->bukti_pembayaran,
            'confirmed' => 0
        ]);

        $response->assertValid();
    }

    public function test_cetak_laporan_zakat() :void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('zakat.laporan'));

        $response->assertOk();
    }
}
