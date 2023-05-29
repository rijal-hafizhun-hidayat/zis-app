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
    public function index(){
        $zakats = DB::table('zakat')
            ->join('sha', 'zakat.sha_id', '=', 'sha.id')
            ->select('zakat.id', 'zakat.nama_donatur', 'zakat.nomor_hp', 'zakat.jenis_zakat', 'sha.nama', 'zakat.berat_beras', 'zakat.jumlah', 'zakat.nominal', 'zakat.bukti_pembayaran', 'zakat.confirmed', 'zakat.created_at AS waktu_zakat', 'zakat.updated_at')
            ->where('nama_donatur', 'like', '%'.request()->search.'%')
            ->latest('waktu_zakat')
            ->get();

        return Inertia::render('Zakat/Index', [
            // 'zakats' => $this->changeDate($zakats)
            'zakats' => $zakats,
            'total' => Zakat::sum('nominal'),
            'totalBeratBeras' => Zakat::whereNotNull('berat_beras')->sum('berat_beras')
        ]);
    }

    public function getImageBuktiPembayaran($path, $image){
        $response = [
            'status' => true,
            'data' => asset('storage/images/'.$path.'/'.$image),
            'code' => 200
        ];

        return response()->json($response, 200);
    }

    public function test(){
        dd(request()->search);
    }

    public function searchZakat(Request $request){
        //$data = Zakat::where('nama_donatur', 'like', '%'.$request->search.'%');

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
        $credential = $this->hasImage();

        //dd($credential);
        $credential['bulan'] = $this->setMonth($credential['bulan']);

        Zakat::create($credential);

        return $this->responseApi(true, 'Berhasil', 'berhasil tambah data', 200);
    }

    public function update(Request $request, $id){
        $credential = $this->hasImage();

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

        //dd($this->setValuePdf());
        $pdf = PDF::loadview('report/index', $this->setValuePdf());
    	return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));
    }

    public function showImage($image){
        return Storage::download('images/Zakat/'.$image);
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }

    private function storeImage(){
        $filename = time().'.'.request()->bukti_pembayaran->getClientOriginalExtension();
        request()->bukti_pembayaran->storeAs('public/images/Zakat', $filename);

        return $filename;
    }

    private function destroyImage($id){
        $image = Zakat::where('id', $id)->value('bukti_pembayaran');

        if(Storage::disk('public')->exists('/images/Zakat/'.$image)){
            Storage::disk('public')->delete('/images/Zakat/'.$image);
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

    private function hasImage(){
        if(request()->hasFile('bukti_pembayaran')){
            $credential = request()->validate([
                'nama_donatur' => 'required|string',
                'nomor_hp' => 'required|numeric|min_digits:10|max_digits:12',
                'jenis_zakat' => 'required|string',
                'sha_id' => 'required|numeric',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
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
                'jenis_zakat' => 'required|string',
                'sha_id' => 'required|numeric',
                'berat_beras' => 'nullable|decimal:0,2',
                'jumlah' => 'nullable|numeric',
                'nominal' => 'nullable|numeric',
                'bulan' => 'numeric',
                'confirmed' => 'required|numeric|max_digits:1'
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
