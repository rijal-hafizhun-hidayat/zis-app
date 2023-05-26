<?php

namespace Tests\Feature;

use App\Models\Shadaqah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShadaqahTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_get_shadaqah_page(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get('/shadaqah');

        $response->assertOk();
    }

    public function test_if_get_shadaqah_page_no_login(): void{
        $response = $this->get('/shadaqah');

        $response->assertFound();
    }

    public function test_store_shadaqah(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get('/shadaqah')->assertOk();

        $response = $this->post(route('shadaqah.store'), [
            'nama_donatur' => 'rijal hafizhun hidayat',
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => 'Barang',
            'keterangan' => '2 lemari bekas',
            'bulan' => 4,
            'nominal' => 43000,
            'bukti_pembayaran' => null,
            'confirmed' => 0
        ]);

        $response->assertOk();
    }

    public function test_validation_store_shadaqah(): void{
        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ])->get(route('shadaqah'));

        $response = $this->post(route('shadaqah.store'), [
            'nama_donatur' => '',
            'nomor_hp' => 6281393784144444444,
            'jenis_bantuan' => 'Cash',
            'keterangan' => null,
            'bulan' => 4,
            'nominal' => 123000,
            'bukti_pembayaran' => null,
            'confirmed' => 0
        ]);

        $response->assertInvalid(['nama_donatur', 'nomor_hp']);
    }

    public function test_delete_shadaqah(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $this->actingAs($user) ->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get('/shadaqah')->assertOk();

        $data = Shadaqah::create([
            'nama_donatur' => 'rijal',
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => 'Cash',
            'keterangan' => null,
            'bulan' => 4,
            'nominal' => 123000,
            'bukti_pembayaran' => null,
            'confirmed' => 0
        ]);

        $response = $this->delete(route('shadaqah.destroy', ['id' => $data->id]));

        $response->assertOk();
    }

    public function test_get_shadaqah_by_id(): void{

        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->get('/shadaqah')->assertOk();

        $shadaqah = Shadaqah::create([
            'nama_donatur' => 'rijal',
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => 'Cash',
            'keterangan' => null,
            'bulan' => 4,
            'nominal' => 123000,
            'bukti_pembayaran' => null,
            'confirmed' => 0
        ]);

        $response = $this->get(route('shadaqah.show', ['id' => $shadaqah->id]));

        $response->assertOk();
    }

    public function test_update_shadaqah(): void{

        $shadaqah = Shadaqah::create([
            'nama_donatur' => 'rijal',
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => 'Cash',
            'keterangan' => null,
            'bulan' => 4,
            'nominal' => 123000,
            'bukti_pembayaran' => null,
            'confirmed' => 0
        ]);

        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data'
        ]);

        $response->put(route('shadaqah.update', ['id' => $shadaqah->id]), [
            'nama_donatur' => 'hafizhun',
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => $shadaqah->jenis_bantuan,
            'keterangan' => $shadaqah->keterangan,
            'bulan' => $shadaqah->bulan,
            'nominal' => $shadaqah->nominal,
            'bukti_pembayaran' => $shadaqah->bukti_pembayaran,
            'confirmed' => $shadaqah->confirmed
        ]);

        $this->assertDatabaseHas('shadaqah', [
            'nama_donatur' => 'hafizhun',
            'nomor_hp' => 628139378414
        ]);
    }

    public function test_store_image_shadaqah(): void{
        $shadaqah = Shadaqah::factory()->create();

        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);

        $response = $this->post(route('shadaqah.store'), [
            'nama_donatur' => $shadaqah->nama_donatur,
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => $shadaqah->jenis_bantuan,
            'keterangan' => $shadaqah->keterangan,
            'bulan' => $shadaqah->bulan,
            'nominal' => $shadaqah->nominal,
            'bukti_pembayaran' => $shadaqah->bukti_pembayaran,
            'confirmed' => $shadaqah->confirmed
        ]);

        $response->assertOk();
    }

    public function test_confirmed_pembayaran_shadaqah(): void{

        $shadaqah = Shadaqah::factory()->create();

        $this->withSession([
            'isLogin' => true,
            'role' => 1
        ]);

        $this->put(route('shadaqah.confirmed', ['id' => $shadaqah->id]), [
            'confirmed' => 1
        ])->assertOk();

        $this->assertDatabaseHas('shadaqah', [
            'confirmed' => 1
        ]);
    }

    public function test_validation_upload_image(): void{
        $shadaqah = Shadaqah::factory()->make();

        $response = $this->withSession([
            'isLogin' => true,
            'role' => 1
        ])->post(route('shadaqah.store'), [
            'nama_donatur' => $shadaqah->nama_donatur,
            'nomor_hp' => 628139378414,
            'jenis_bantuan' => $shadaqah->jenis_bantuan,
            'keterangan' => $shadaqah->keterangan,
            'bulan' => $shadaqah->bulan,
            'nominal' => $shadaqah->nominal,
            'bukti_pembayaran' => $shadaqah->bukti_pembayaran,
            'confirmed' => $shadaqah->confirmed
        ]);

        $response->assertOk();
    }

    public function test_cetak_laporan_shadaqah(): void{
        $user = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'password' => '$2y$10$tnj4cSkso6MebjPHD.S8Me4U1EA9hH0vWvZUKCPcamR2InWVa1gmW',
            'role' => 1
        ]);

        $response = $this->actingAs($user)->withSession([
            'isLogin' => true,
            'role' => $user->role
        ])->post(route('shadaqah.laporan'));

        $response->assertOk();
    }
}