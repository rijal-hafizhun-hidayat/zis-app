<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class InfaqController extends Controller
{
    public function index(){
        return Inertia::render('Infaq/Index', [
            'infaqs' => Infaq::select('id', 'nama_donatur', 'nomor_hp', 'metode_pembayaran', 'nominal', 'bukti_pembayaran', 'confirmed', 'created_at')->latest()->get(),
            'total' => Infaq::sum('nominal')
        ]);
    }

    public function create(){
        return Inertia::render('Infaq/Create');
    }

    public function show($id){
        return Inertia::render('Infaq/Edit', [
            'infaq' => Infaq::find($id)
        ]);
    }

    public function store(){
        $credential = $this->hasImage();

        $credential['bulan'] = $this->setMonth($credential['bulan']);

        Infaq::create($credential);

        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function update(Request $request, $id){
        $credential = $this->hasImage();

        if($request->hasFile('bukti_pembayaran')){
            $this->destroyImage($id);

            Infaq::findOrFail($id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }
        else{
            Infaq::findOrFail($id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }

        return $this->responseApi(false, 'Gagal', 'gagal update data', 400);
    }

    public function delete($id){
        $this->destroyImage($id);

        Infaq::destroy($id);

        return $this->responseApi(true, 'Berhasil', 'berhasil hapus data', 200);
    }

    public function confirmed($id){

        Infaq::where('id', $id)->update(['confirmed' => 1]);

        return $this->responseApi(true, 'Berhasil', 'konfirmasi pembayaran berhasil', 200);
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }

    private function storeImage(){
        $filename = time().'.'.request()->bukti_pembayaran->getClientOriginalExtension();
        
        $file = request()->file('bukti_pembayaran');
        $file->move(base_path('/public/image/Infaq'), $filename);

        return $filename;
    }

    private function destroyImage($id){
        $image = Infaq::where('id', $id)->value('bukti_pembayaran');

        if(file_exists(base_path('/public/image/Infaq/'.$image))){
            unlink(base_path('/public/image/Infaq/'.$image));
            return true;
        }
        else{
            return false;
        }
    }

    private function responseApi($status, $title, $message, $code){
        $response = [
            'status' => $status,
            'title' => $title,
            'message' => $message,
            'code' => $code
        ];

        return response()->json($response, $code);
    }

    private function hasImage(){
        if(request()->hasFile('bukti_pembayaran')){
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
                'metode_pembayaran' => 'required|string',
                'nominal' => 'required|numeric',
                'bulan' => 'numeric',
                'bukti_pembayaran' => 'mimes:jpg,jpeg,png',
                'confirmed' => 'required|numeric|max_digits:1'
            ]);

            $credential['bukti_pembayaran'] = $this->storeImage();
        }
        else{
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
                'metode_pembayaran' => 'required|string',
                'nominal' => 'required|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
            ]);
        }

        return $credential;
    }
}
