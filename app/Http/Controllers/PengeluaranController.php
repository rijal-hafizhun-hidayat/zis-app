<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Shadaqah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PengeluaranController extends Controller
{
    public function index(Request $request){
        $pengeluaran = $this->setQueryPengeluaran($request->filter);
        return Inertia::render('Pengeluaran/Index', [
            'pengeluarans' => $pengeluaran->latest()->get(),
            'total' => $pengeluaran->sum('nominal'),
            'totalBeratBeras' => $pengeluaran->sum('berat_beras'),
            'totalMustahiq' => $pengeluaran->sum('jumlah_mustahiq')
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
        $credential = $this->formRequest();
        //dd($credential);
        $credential['bulan'] = $this->setMonth($credential['bulan']);
        Pengeluaran::create($credential);
        if($credential['jenis_dana'] == 'Shadaqah'){
            $this->setIsPengeluaranShadaqah($credential['nama_barang'], 1);
        }
        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function destroy($id){
        $this->destroyImage($id);
        // Pengeluaran::destroy($id);
        $pengeluaran = Pengeluaran::find($id);
        dd(is_null($pengeluaran->id_shadaqah));
        //$flight->delete();
        return $this->responseApi(true, 'Berhasil', 'berhasil hapus data', 200);
    }

    public function update(Request $request, $id){
        $credential = $this->formRequest();
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

    private function setIsPengeluaranShadaqah($keterangan, $status){
        try {
            Shadaqah::where('keterangan', $keterangan)->update(['is_pengeluaran' => $status]);
            return true;
        } catch (Exception $e) {
            return false;            
        }
    }

    private function setQueryPengeluaran($request){
        $queryPengeluaran = DB::table('pengeluaran');
        if(!empty($request)){
            if(!empty($request['bulan'])){
                $queryPengeluaran->where('bulan', $request['bulan']);
            }
            if(!empty($request['jenis_dana'])){
                $queryPengeluaran->where('jenis_dana', $request['jenis_dana']);
            }
        }
                
        return $queryPengeluaran;
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
        if(is_file(base_path('/public/image/Pengeluaran/'.$image))){
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

    private function formRequest(){
        if(request()->hasFile('bukti_pengeluaran')){
            $credential = request()->validate([
                'nama_organisasi' => 'nullable|string',
                'kebutuhan' => 'required|string',
                'jenis_dana' => 'required|string',
                'berat_beras' => 'exclude_if:jenis_dana,Shadaqah|required_if:nominal,false|nullable|decimal:0,2',
                'nama_barang' => 'exclude_unless:jenis_dana,Shadaqah|required|string',
                'jumlah_mustahiq' => 'nullable|numeric',
                'nominal' => 'exclude_if:jenis_dana,Shadaqah|required_if:berat_beras,false|nullable|numeric',
                'bulan' => 'numeric',
                'bukti_pengeluaran' => 'mimes:jpg,jpeg,png',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_organisasi.string' => 'wajib dalam bentuk teks',
                'kebutuhan.required' => 'wajib diisi',
                'kebutuhan.string' => 'wajib dalam bentuk teks',
                'jenis_dana.required' => 'wajib diisi',
                'jenis_dana.string' => 'wajib dalam bentuk teks',
                'berat_beras.decimal' => 'wajib dalam bentuk decimal 2 angka dibelakang koma',
                'jumlah_mustahiq.numeric' => 'wajib dalam bentuk angka',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'bukti_pengeluaran.mimes' => 'gambar wajib dengan format .jpg .jpeg .png',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka'
            ]);
            $credential['bukti_pengeluaran'] = $this->storeImage();

        }
        else{
            $credential = request()->validate([
                'nama_organisasi' => 'nullable|string',
                'kebutuhan' => 'required|string',
                'jenis_dana' => 'required|string',
                'berat_beras' => 'exclude_if:jenis_dana,Shadaqah|required_without:nominal|nullable|decimal:0,2',
                'nama_barang' => 'exclude_unless:jenis_dana,Shadaqah|required|string',
                'jumlah_mustahiq' => 'nullable|numeric',
                'nominal' => 'exclude_if:jenis_dana,Shadaqah|required_without:berat_beras|nullable|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_organisasi.string' => 'wajib dalam bentuk teks',
                'kebutuhan.required' => 'wajib diisi',
                'kebutuhan.string' => 'wajib dalam bentuk teks',
                'jenis_dana.required' => 'wajib diisi',
                'jenis_dana.string' => 'wajib dalam bentuk teks',
                'berat_beras.decimal' => 'wajib dalam bentuk decimal 2 angka dibelakang koma',
                'jumlah_mustahiq.numeric' => 'wajib dalam bentuk angka',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka',
                'required_without' => 'wajib di isi jika salah satu form berat beras atau nominal diisi'
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
