<?php

namespace App\Http\Controllers;

use App\Models\Shadaqah;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShadaqahController extends Controller
{
    public function index(){
        return Inertia::render('Shadaqah/Index', [
            'shadaqahs' => Shadaqah::latest()->get(),
            'total' => Shadaqah::sum('nominal')
        ]);
    }

    public function create(){
        return Inertia::render('Shadaqah/Create');
    }

    public function store(){
        $credential = $this->hasImage();

        //dd($credential);

        $credential['bulan'] = $this->setMonth($credential['bulan']);

        Shadaqah::create($credential);

        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function show($id){
        return Inertia::render('Shadaqah/Edit', [
            'shadaqah' => Shadaqah::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id){
        $credential = $this->hasImage();

        if($request->hasFile('bukti_pembayaran')){
            $this->destroyImage($id);
            
            Shadaqah::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }
        else{
            Shadaqah::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }

        return $this->responseApi(false, 'Gagal', 'gagal update data', 400);
    }

    public function destroy($id){
        $this->destroyImage($id);

        Shadaqah::destroy($id);

        return $this->responseApi(true, 'Berhasil', 'berhasil hapus data', 200);
    }

    private function responseApi($status, $title, $text, $code){
        $response = [
            'status' => $status,
            'title' => $title,
            'text' => $text,
            'code' => $code
        ];

        return response()->json($response, $code);
    }

    public function confirmed(Request $request, $id){
        //Shadaqah::where('id', $id)->update(['confirmed' => 1]);
        $shadaqah = Shadaqah::findOrFail($id);

        $shadaqah->update(['confirmed' => $request->confirmed]);

        return $this->responseApi(true, 'Berhasil', 'konfirmasi pembayaran berhasil', 200);
    }

    private function storeImage(){
        $filename = time().'.'.request()->bukti_pembayaran->getClientOriginalExtension();

        $file = request()->file('bukti_pembayaran');
        $file->move(base_path('/public/image/Shadaqah'), $filename);

        return $filename;
    }

    private function destroyImage($id){
        $image = Shadaqah::where('id', $id)->value('bukti_pembayaran');

        if(file_exists(base_path('/public/image/Shadaqah/'.$image))){
            unlink(base_path('/public/image/Shadaqah/'.$image));
            return true;
        }
        else{
            return false;
        }
    }

    private function hasImage(){
        if(request()->hasFile('bukti_pembayaran')){
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
                'jenis_bantuan' => 'required|string',
                'nominal' => 'nullable|numeric',
                'keterangan' => 'nullable|string',
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
                'jenis_bantuan' => 'required|string',
                'nominal' => 'nullable|numeric',
                'keterangan' => 'nullable|string',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric'
            ]);
        }

        return $credential;
    }
    
    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }
}
