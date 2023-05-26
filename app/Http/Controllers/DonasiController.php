<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use App\Models\Pengeluaran;
use App\Models\Sha;
use App\Models\Shadaqah;
use App\Models\Zakat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonasiController extends Controller
{
    public function index(){
        return Inertia::render('Donasi/Index');
    }

    public function getDonasi(){
        $response = [
            'status' => true,
            'data' => [
                'zakat' => Pengeluaran::where('jenis_dana', 'Zakat')->latest()->get(),
                'infaq' => Pengeluaran::where('jenis_dana', 'Infaq')->latest()->get(),
                'shadaqah' => Pengeluaran::where('jenis_dana', 'Shadaqah')->latest()->get()
            ],
            'code' => 200,
        ];

        return response()->json($response, 200);
    }

    public function store(){
        // dd(request()->file('nama_donatur'));
        $credential = $this->formRequest();

        if($credential['jenis_donasi'] == 'Zakat Fitrah' || $credential['jenis_donasi'] == 'Zakat Maal'){
            $this->storeZakat($credential);

            return $this->responseStoreDonasi(200, 'berhasil', 'Terima Kasih Telah Bersubangsi');
        }
        else if($credential['jenis_donasi'] == 'Infaq'){
            $this->storeInfaq($credential);

            return $this->responseStoreDonasi(200, 'berhasil', 'Terima Kasih Telah Bersubangsi');
        }
        else if($credential['jenis_donasi'] == 'Shadaqah'){
            $this->storeShadaqah($credential);

            return $this->responseStoreDonasi(200, 'berhasil', 'Terima Kasih Telah Bersubangsi');
        }        
    }

    public function getSatuan(){
        $response = [
            'status' => true,
            'data' => Sha::latest()->get(),
            'code' => 200
        ];

        return response()->json($response, 200);
    }

    public function getNominal($id){
        $response = [
            'status' => true,
            'data' => Sha::select('harga')->where('id', $id)->first(),
            'code' => 200
        ];

        return response()->json($response, 200);
    }

    private function storeZakat($credential){
        return Zakat::create([
            'nama_donatur' => $credential['nama_donatur'],
            'jenis_zakat' => $credential['jenis_donasi'],
            'nomor_hp' => $credential['nomor_hp'],
            'sha_id' => $credential['sha_id'],
            'berat_beras' => $credential['berat_beras'],
            'jumlah' => $credential['jumlah'],
            'nominal' => $credential['nominal'],
            'bulan' => $this->setMonth($credential['bulan']),
            'bukti_pembayaran' => $credential['bukti_donasi'],
            'confirmed' => $credential['confirmed']
        ]);
    }

    private function storeInfaq($credential){
        return Infaq::create([
            'nama_donatur' => $credential['nama_donatur'],
            'nomor_hp' => $credential['nomor_hp'],
            'metode_pembayaran' => $credential['metode_pembayaran'],
            'nominal' => $credential['nominal'],
            'bulan' => $this->setMonth($credential['bulan']),
            'bukti_pembayaran' => $credential['bukti_donasi'],
            'confirmed' => $credential['confirmed']
        ]);
    }

    private function storeShadaqah($credential){
        return Shadaqah::create([
            'nama_donatur' => $credential['nama_donatur'],
            'nomor_hp' => $credential['nomor_hp'],
            'jenis_bantuan' => 'Cash',
            'keterangan' => null,
            'bulan' => $this->setMonth($credential['bulan']),
            'nominal' => $credential['nominal'],
            'bukti_pembayaran' => $credential['bukti_donasi'],
            'confirmed' => $credential['confirmed']
        ]);
    }

    private function responseStoreDonasi($code, $title, $text){
        $response = [
            'code' => $code,
            'title' => $title,
            'text' => $text
        ];

        return response()->json($response, $code);
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }

    private function formRequest(){
        $credential = request()->validate([
            'nama_donatur' => 'required|string',
            'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
            'jenis_donasi' => 'required|string',
            'sha_id' => 'nullable|numeric',
            'jumlah' => 'nullable|numeric',
            'nominal' => 'nullable|numeric',
            'berat_beras' => 'nullable|decimal:0,2',
            'bulan' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'bukti_donasi' => 'required|image',
            'confirmed' => 'required|numeric'
        ]);

        if($credential['jenis_donasi'] == 'Zakat Fitrah' || $credential['jenis_donasi'] == 'Zakat Maal'){
            $path = 'Zakat/';
        }
        else if($credential['jenis_donasi'] == 'Infaq'){
            $path = 'Infaq/';
        }
        else if($credential['jenis_donasi'] == 'Shadaqah'){
            $path = 'Shadaqah/';
        }

        $credential['bukti_donasi'] = $this->storeImage($path);

        return $this->setForm($credential);
    }

    private function storeImage($path){
        $filename = time().'.'.request()->bukti_donasi->getClientOriginalExtension();
        request()->bukti_donasi->storeAs('public/images/'.$path, $filename);

        return $filename;
    }

    private function setForm($credential){

        if($credential['jenis_donasi'] == 'Zakat Fitrah'){
            if($credential['sha_id'] == 1){
                $credential['nominal'] = null;
            }
            else{
                $credential['berat_beras'] = null; 
            }

            return $credential;
        }
        else if($credential['jenis_donasi'] == 'Zakat Maal'){
            $credential['sha_id'] = 2;
            $credential['jumlah'] = null;
            $credential['berat_beras'] = null;

            return $credential;
        }
        else if($credential['jenis_donasi'] == 'Infaq'){
            $credential['jumlah'] = null;
            $credential['berat_beras'] = null;

            return $credential;
        }
        else if($credential['jenis_donasi'] == 'Shadaqah'){
            $credential['jumlah'] = null;
            $credential['berat_beras'] = null;

            return $credential;
        }

        return $credential;
    }
}
