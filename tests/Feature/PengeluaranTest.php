<?php

namespace Tests\Feature;

use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PengeluaranTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_pengeluaran_page(): void
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
        ])->get(route('pengeluaran'));

        $response->assertOk();
    }

    public function test_get_pengeluaran_page_not_authenticate(): void{
        $response = $this->get(route('pengeluaran'));

        $response->assertFound();
    }

    public function test_store_pengeluaran(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $pengeluaran = Pengeluaran::factory()->make();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('pengeluaran.store'), [
            'nama_organisasi' => '',
            'kebutuhan' => $pengeluaran->kebutuhan,
            'jenis_dana' => 'Zakat',
            'berat_beras' => $pengeluaran->berat_beras,
            'jumlah_mustahiq' => $pengeluaran->jumlah_mustahiq,
            'nominal' => '',
            'bulan' => $pengeluaran->bulan,
            'bukti_pengeluaran' => $pengeluaran->bukti_pengeluaran,
            'confirmed' => 0
        ]);

        $response->assertOk();
    }

    public function test_validation_store_pengeluaran(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $pengeluaran = Pengeluaran::factory()->make();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('pengeluaran.store'), [
            'nama_organisasi' => '',
            'kebutuhan' => '',
            'jenis_dana' => '',
            'berat_beras' => $pengeluaran->berat_beras,
            'jumlah_mustahiq' => $pengeluaran->jumlah_mustahiq,
            'nominal' => '',
            'bulan' => $pengeluaran->bulan,
            'bukti_pengeluaran' => $pengeluaran->bukti_pengeluaran,
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_update_pengeluaran_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $pengeluaran = Pengeluaran::factory()->create();

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('pengeluaran.update', ['id' => $pengeluaran->id]), [
            'nama_organisasi' => '',
            'kebutuhan' => $pengeluaran->kebutuhan,
            'jenis_dana' => 'Infaq',
            'berat_beras' => '',
            'jumlah_mustahiq' => '',
            'nominal' => 450000,
            'bulan' => $pengeluaran->bulan,
            'bukti_pengeluaran' => $pengeluaran->bukti_pengeluaran,
            'confirmed' => 0
        ]);

        $this->assertDatabaseHas('pengeluaran', [
            'jenis_dana' => 'Infaq',
            'nominal' => 450000
        ]);
    }

    public function test_validation_update_pengeluaran_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $pengeluaran = Pengeluaran::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->put(route('pengeluaran.update', ['id' => $pengeluaran->id]), [
            'nama_organisasi' => '',
            'kebutuhan' => '',
            'jenis_dana' => 'Infaq',
            'berat_beras' => '',
            'jumlah_mustahiq' => '',
            'nominal' => '450000',
            'bulan' => $pengeluaran->bulan,
            'bukti_pengeluaran' => $pengeluaran->bukti_pengeluaran,
            'confirmed' => 0
        ]);

        $response->assertInvalid();
    }

    public function test_delete_pengeluaran_by_id(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $pengeluaran = Pengeluaran::factory()->create();

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->delete(route('pengeluaran.destroy', ['id' => $pengeluaran->id]));

        $response->assertOk();
    }

    public function test_cetak_laporan_pengeluaran(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('pengeluaran.laporan'));

        $response->assertOk();
    }
}
