<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(){
        return Inertia::render('Pengeluaran/Index', [
            'pengeluarans' => Pengeluaran::latest()->get(),
            'total' => Pengeluaran::sum('nominal'),
            'totalBeratBeras' => Pengeluaran::sum('berat_beras'),
            'totalMustahiq' => Pengeluaran::sum('jumlah_mustahiq')
        ]);
    }

    public function create(){
        return Inertia::render('Pengeluaran/Create');
    }

    public function show($id){
        return Inertia::render('Pengeluaran/Edit', [
            'pengeluaran' => Pengeluaran::find($id)
        ]);
    }

    public function store(){
        $credential = $this->hasImage();

        $credential['bulan'] = $this->setMonth($credential['bulan']);

        Pengeluaran::create($credential);

        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function destroy($id){
        $this->destroyImage($id);

        Pengeluaran::destroy($id);

        return $this->responseApi(true, 'Berhasil', 'berhasil hapus data', 200);
    }

    public function update(Request $request, $id){

        $credential = $this->hasImage();

        if($request->hasFile('bukti_pengeluaran')){
            $this->destroyImage($id);

            Pengeluaran::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }
        else{
            Pengeluaran::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }

        return $this->responseApi(false, 'Gagal', 'gagal update data', 400);
    }

    public function confirmed($id){
        Pengeluaran::where('id', $id)->update(['confirmed' => 1]);

        return $this->responseApi(true, 'Berhasil', 'konfirmasi pengeluaran berhasil', 200);
    }

    private function storeImage(){
        $filename = time().'.'.request()->bukti_pengeluaran->getClientOriginalExtension();

        $file = request()->file('bukti_pengeluaran');
        $file->move(base_path('/public/image/Pengeluaran'), $filename);

        return $filename;
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

    private function destroyImage($id){
        $image = Pengeluaran::where('id', $id)->value('bukti_pengeluaran');

        if(file_exists(base_path('/public/image/Pengeluaran/'.$image))){
            unlink(base_path('/public/image/Pengeluaran/'.$image));
            return true;
        }
        else{
            return false;
        }
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }

    private function hasImage(){
        if(request()->hasFile('bukti_pengeluaran')){
            $credential = request()->validate([
                'nama_organisasi' => 'nullable|string',
                'kebutuhan' => 'required|string',
                'jenis_dana' => 'required|string',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah_mustahiq' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
                'bulan' => 'numeric',
                'bukti_pengeluaran' => 'mimes:jpg,jpeg,png',
                'confirmed' => 'required|numeric|max_digits:1'
            ]);

            $credential['bukti_pengeluaran'] = $this->storeImage();

        }
        else{
            $credential = request()->validate([
                'nama_organisasi' => 'nullable|string',
                'kebutuhan' => 'required|string',
                'jenis_dana' => 'required|string',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah_mustahiq' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
            ]);
        }

        return $this->setForm($credential);
    }

    private function setForm($credential){
        if($credential['jenis_dana'] == 'Infaq' || $credential['jenis_dana'] == 'Shadaqah'){
            $credential['nama_organisasi'] = null;
        }

        return $credential;
    }
}
