<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use App\Models\Pengeluaran;
use App\Models\Sha;
use App\Models\Shadaqah;
use App\Models\Zakat;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;

class ZakatController extends Controller
{
    public function index(Request $request){

        $queryZakats = DB::table('zakat')
            ->join('sha', 'zakat.sha_id', '=', 'sha.id')
            ->select('zakat.id', 'zakat.nama_donatur', 'zakat.bulan', 'zakat.nomor_hp', 'zakat.jenis_zakat', 'sha.nama', 'zakat.berat_beras', 'zakat.jumlah', 'zakat.nominal', 'zakat.bukti_pembayaran', 'zakat.confirmed', 'zakat.created_at AS waktu_zakat', 'zakat.updated_at');
        if(!empty($request->all())){
            $zakats = $this->setQueryZakats($queryZakats, $request->filters);
        }
        else{
            $zakats = $this->setQueryZakats($queryZakats, null);
        }
        return Inertia::render('Zakat/Index', [
            'zakats' => $zakats->latest('waktu_zakat')->get(),
            'total' => $zakats->sum('nominal'),
            'totalBeratBeras' => $zakats->whereNotNull('berat_beras')->sum('berat_beras')
        ]);
    }

    public function searchZakat(Request $request){
        $response = [
            'status' => true,
            'data' => Zakat::where('nama_donatur', 'like', '%'.$request->search.'%')->paginate(5)
        ];
        return response()->json($response, 200);
    }

    public function create(){
        return Inertia::render('Zakat/Create', [
            'shas' => Sha::select('id', 'nama')->get()
        ]);
    }

    public function show($id){
        return Inertia::render('Zakat/Edit', [
            'zakat' => Zakat::find($id),
            'shas' => Sha::all()
        ]);
    }

    public function store(){
        $credential = $this->formRequest();
        $credential['bulan'] = $this->setMonth($credential['bulan']);
        Zakat::create($credential);
        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function update(Request $request, $id){
        $credential = $this->formRequest();
        if($request->hasFile('bukti_pembayaran')){
            $this->destroyImage($id);
            Zakat::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }
        else{
            Zakat::where('id', $id)->update($credential);
            return $this->responseApi(true, 'Berhasil', 'berhasil update data', 200);
        }
        return $this->responseApi(false, 'Gagal', 'gagal update data', 400);
    }

    public function destroy($id){  
        $this->destroyImage($id);
        Zakat::destroy($id);
        return $this->responseApi(true, 'Berhasil', 'berhasil hapus data', 200);
    }

    public function getNominalSha($id){
        $response = [
            'status' => true,
            'data' => Sha::select('harga')->where('id', $id)->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function confirmed($id){
        Zakat::where('id', $id)->update(['confirmed' => 1]);
        return $this->responseApi(true, 'Berhasil', 'konfirmasi pembayaran berhasil', 200);
    }

    public function exportPdf(){
        $pdf = PDF::loadview('report/index', $this->setValuePdf());
    	return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));
    }

    public function showImage($image){
        return Storage::download('image/Zakat/'.$image);
    }

    private function setQueryZakats($queryZakats, $request){
        if(!empty($request['nama_donatur'])){
            $queryZakats->where('zakat.nama_donatur', 'like', '%'.$request['nama_donatur'].'%');
        }
        if(!empty($request['bulan'])){
            $queryZakats->where('zakat.bulan', $request['bulan']);
        }
        if(!empty($request['satuan'])){
            $queryZakats->where('zakat.sha_id', $request['satuan']);
        }
        if(!empty($request['jenis_zakat'])){
            $queryZakats->where('zakat.jenis_zakat', $request['jenis_zakat']);
        }

        return $queryZakats;
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }

    private function storeImage(){
        $filename = time().'.'.request()->bukti_pembayaran->getClientOriginalExtension();
        $file = request()->file('bukti_pembayaran');
        $file->move(base_path('/public/image/Zakat'), $filename);
        return $filename;
    }

    private function destroyImage($id){
        $image = Zakat::where('id', $id)->value('bukti_pembayaran');
        if(is_file(base_path('/public/image/Zakat/'.$image))){
            unlink(base_path('/public/image/Zakat/'.$image));
            return true;
        }
        else{
            return false;
        }
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

    private function formRequest(){
        if(request()->hasFile('bukti_pembayaran')){
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:13',
                'jenis_zakat' => 'required|string',
                'sha_id' => 'required|numeric',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
                'bulan' => 'numeric',
                'bukti_pembayaran' => 'mimes:jpg,jpeg,png',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_donatur.required' => 'wajib diisi',
                'nama_donatur.string' => 'wajib dalam bentuk teks',
                'nomor_hp.required' => 'wajib diisi',
                'nomor_hp.numeric' => 'wajib dalam bentuk angka',
                'nomor_hp.min_digits' => 'minimal nomor hp 10 digit',
                'nomor_hp.max_digits' => 'maximal nomor hp 12 digit',
                'jenis_zakat.required' => 'wajib diisi',
                'jenis_zakat.string' => 'wajib dalam bentuk teks',
                'sha_id.required' => 'wajib diisi',
                'sha_id.numeric' => 'wajib dalam bentuk angka',
                'berat_beras.decimal' => 'desimal minimal 2 angka dibelakang koma',
                'jumlah.numeric' => 'wajib dalam bentuk angka',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'bukti_pembayaran.mimes' => 'gambar wajib dengan format .jpg .jpeg .png',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka',
            ]);
            $credential['bukti_pembayaran'] = $this->storeImage();

        }
        else{
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
                'jenis_zakat' => 'required|string',
                'sha_id' => 'required|numeric',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
            ], [
                'nama_donatur.required' => 'wajib diisi',
                'nama_donatur.string' => 'wajib dalam bentuk teks',
                'nomor_hp.required' => 'wajib diisi',
                'nomor_hp.numeric' => 'wajib dalam bentuk angka',
                'nomor_hp.min_digits' => 'minimal nomor hp 10 digit',
                'nomor_hp.max_digits' => 'maximal nomor hp 12 digit',
                'jenis_zakat.required' => 'wajib diisi',
                'jenis_zakat.string' => 'wajib dalam bentuk teks',
                'sha_id.required' => 'wajib diisi',
                'sha_id.numeric' => 'wajib dalam bentuk angka',
                'berat_beras.decimal' => 'desimal minimal 2 angka dibelakang koma',
                'jumlah.numeric' => 'wajib dalam bentuk angka',
                'nominal.numeric' => 'wajib dalam bentuk angka',
                'bulan.numeric' => 'wajib dalam bentuk angka',
                'confirmed.required' => 'wajib diisi',
                'confirmed.numeric' => 'wajib dalam bentuk angka',
            ]);
        }
        return $this->setForm($credential);
    }

    private function setForm($credential){
        if($credential['jenis_zakat'] == 'Zakat Maal'){
            $credential['sha_id'] = 2;
            $credential['jumlah'] = null;
            $credential['berat_beras'] = null;
        }
        else if($credential['jenis_zakat'] == 'Zakat Fitrah' && $credential['sha_id'] == 1){
            $credential['nominal'] = null;
        }
        else if($credential['jenis_zakat'] == 'Zakat Fitrah' && $credential['sha_id'] == 2){
            $credential['berat_beras'] = null;
        }
        return $credential;
    }

    private function formatRp($angka){
        $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }

    private function totalSaldoUang(){
        return (Zakat::where('sha_id', 2)->sum('nominal') + Infaq::sum('nominal') + Shadaqah::sum('nominal')) - Pengeluaran::whereMonth('created_at', date("m"))->sum('nominal');
    }

    private function totalSaldoBeras(){
        return Zakat::whereNotNull('berat_beras')->sum('berat_beras') - Pengeluaran::whereMonth('created_at', date("m"))->whereNotNull('berat_beras')->sum('berat_beras');
    }

    private function setValuePdf(){
        //ZAKAT
        $zakatCash = $this->formatRp((int)Zakat::where('sha_id', 2)->sum('nominal'));
        $zakatBeras = Zakat::where('sha_id', 1)->sum('jumlah')*2.5;
        $zakatMuzakkiCash = Zakat::where('sha_id', 2)->count();
        $zakatMuzakkiBeras = Zakat::where('sha_id', 1)->count();
        $totalZakatMuzakki = Zakat::count();

        //INFAQ
        $infaqCash = $this->formatRp((int)Infaq::sum('nominal'));
        $totalInfaqMuzakki = Infaq::count();

        //SHADAQAH
        $shadaqahCash = $this->formatRp((int)Shadaqah::sum('nominal'));
        $totalShadaqahMuzakki = Shadaqah::count();

        //INFAQ + SHADAQAH
        $totalInfaqShadaqahCash = $this->formatRp((int)(Shadaqah::sum('nominal') + Infaq::sum('nominal')));
        $totalInfaqShadaqahMuzakki = $this->formatRp((int)(Shadaqah::count() + Infaq::count()));

        //PENGELUARAN
        $pengeluarans = Pengeluaran::select('kebutuhan', 'nominal', 'berat_beras', 'jumlah_mustahiq')->get();
        $pengeluaranMustahikCash = Pengeluaran::where('berat_beras', null)->whereMonth('created_at', date("m"))->sum('nominal');
        $totalPengeluaranMustahik = Pengeluaran::sum('jumlah_mustahiq');
        $totalPengeluaran = $this->formatRp((int)Pengeluaran::whereMonth('created_at', date("m"))->sum('nominal'));
        $totalPengeluaranBeras = Pengeluaran::whereMonth('created_at', date("m"))->whereNotNull('berat_beras')->sum('berat_beras');
        
        //TOTAL SALDO
        $totalSaldoUang = $this->formatRp($this->totalSaldoUang());
        $totalSaldoBeras = $this->totalSaldoBeras();
        
        //TANGGAL PEMBUATAN LAPORAN
        $date = date("d").' '.$this->setMonth((int)date("m")-1).' '.date("Y");

        return compact(
            'zakatCash',
            'zakatBeras',
            'zakatMuzakkiCash',
            'zakatMuzakkiBeras',
            'totalZakatMuzakki',
            'infaqCash',
            'totalInfaqMuzakki',
            'shadaqahCash',
            'totalShadaqahMuzakki',
            'totalInfaqShadaqahCash',
            'totalInfaqShadaqahMuzakki',
            'pengeluarans',
            'pengeluaranMustahikCash',
            'totalPengeluaran',
            'totalPengeluaranMustahik',
            'totalPengeluaranBeras',
            'totalSaldoUang',
            'totalSaldoBeras',
            'date'
        );
    }
}
