<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;

class InfaqController extends Controller
{
    public function index(Request $request){
        $infaqs = $this->setQueryInfaq($request->filter);
        //$infaqs = $getInfaqs->get();
        return Inertia::render('Infaq/Index', [
            'infaqs' => $infaqs->latest()->get(),
            'total' => $infaqs->sum('nominal')
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
        $credential = $this->formRequest();
        $credential['bulan'] = $this->setMonth($credential['bulan']);
        Infaq::create($credential);
        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function update(Request $request, $id){
        $credential = $this->formRequest();
        if($request->hasFile('bukti_pembayaran')){
            $this->destroyImage($id);
            Infaq::findOrFail($id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil ubah data', 200);
        }
        else{
            Infaq::findOrFail($id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil ubah data', 200);
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

    private function setQueryInfaq($request){
        $queryInfaq = DB::table('infaq');
        if(!empty($request)){
            if(!empty($request['nama_donatur'])){
                $queryInfaq->where('nama_donatur', 'like', '%'.$request['nama_donatur'].'%');
            }
            if(!empty($request['bulan'])){
                $queryInfaq->where('bulan', $request['bulan']);
            }
            if(!empty($request['metode_pembayaran'])){
                $queryInfaq->where('metode_pembayaran', $request['metode_pembayaran']);
            }
        }
        
        return $queryInfaq;
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
        if(is_file(base_path('/public/image/Infaq/'.$image))){
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

    private function formRequest(){
        if(request()->hasFile('bukti_pembayaran')){
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:13',
                'metode_pembayaran' => 'required|string',
                'nominal' => 'required|numeric',
                'bulan' => 'numeric',
                'bukti_pembayaran' => 'mimes:jpg,jpeg,png',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_donatur.required' => 'wajib diisi',
                'nama_donatur' => 'wajib dalam bentuk kalimat',
                'nomor_hp.required' => 'wajib diisi',
                'nomor_hp.numeric' => 'wajib dalam bentuk angka',
                'nomor_hp.min_digits' => 'minimal nomor hp 10 digit',
                'nomor_hp.max_digits' => 'maximal nomor hp 12 digit',
                'metode_pembayaran.required' => 'wajib diisi',
                'metode_pembayaran.string' => 'wajib dalam bentuk kalimat',
                'nominal.required' => 'wajib diisi',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'bukti_pembayaran.mimes' => 'gambar wajib dengan format .jpg .jpeg .png',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka'
            ]);

            $credential['bukti_pembayaran'] = $this->storeImage();
        }
        else{
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:13',
                'metode_pembayaran' => 'required|string',
                'nominal' => 'required|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_donatur.required' => 'wajib diisi',
                'nama_donatur' => 'wajib dalam bentuk kalimat',
                'nomor_hp.required' => 'wajib diisi',
                'nomor_hp.numeric' => 'wajib dalam bentuk angka',
                'nomor_hp.min_digits' => 'minimal nomor hp 10 digit',
                'nomor_hp.max_digits' => 'maximal nomor hp 12 digit',
                'metode_pembayaran.required' => 'wajib diisi',
                'metode_pembayaran.string' => 'wajib dalam bentuk kalimat',
                'nominal.required' => 'wajib diisi',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka'
            ]);
        }
        return $credential;
    }
}
