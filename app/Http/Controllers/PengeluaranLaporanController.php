<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class PengeluaranLaporanController extends Controller
{
    public function index(){
        return Inertia::render('Pengeluaran/Laporan/Index');
    }

    public function generateLaporan(Request $request){
        if($request->filled('bulan')){
            if(Pengeluaran::where('bulan', $request->bulan)->exists()){
                $pengeluaranUangs = Pengeluaran::whereNotNull('nominal')->where('bulan', $request->bulan)->get();
                $pengeluaranBerass = Pengeluaran::whereNotNull('berat_beras')->where('bulan', $request->bulan)->get();
                $totalSaldoPengeluaranUang = $this->formatRp((int)Pengeluaran::where('bulan', $request->bulan)->sum('nominal'));
                $totalSaldoPengeluaranBeras = Pengeluaran::where('bulan', $request->bulan)->sum('berat_beras');
                $jumlahMustahiq = Pengeluaran::where('bulan', $request->bulan)->sum('jumlah_mustahiq');
            }
            else{
                return redirect()->route('pengeluaran.laporan')->with('message', 'Data Tidak Ditemukan');
            }
        }
        else{
            $pengeluaranUangs = Pengeluaran::whereNotNull('nominal')->get();
            $pengeluaranBerass = Pengeluaran::whereNotNull('berat_beras')->get();
            $totalSaldoPengeluaranUang = $this->formatRp((int)Pengeluaran::sum('nominal'));
            $totalSaldoPengeluaranBeras = Pengeluaran::sum('berat_beras');
            $jumlahMustahiq = Pengeluaran::sum('jumlah_mustahiq');
        }

        $date = date("d").' '.$this->setMonth((int)date("m")-1).' '.date("Y");
        $bulan = $request->bulan;

        $pdf = PDF::loadview('report/Pengeluaran/index', compact(
            'pengeluaranUangs',
            'pengeluaranBerass',
            'totalSaldoPengeluaranUang',
            'totalSaldoPengeluaranBeras',
            'date',
            'bulan',
            'jumlahMustahiq'
        ));

        return $pdf->stream("laporan_pengeluaran.pdf", array("Attachment" => false));
        //dd($request->filled('bulan'), $request->bulan);
        // if(Pengeluaran::where('bulan', $request->bulan)->exists()){
           
        // }
        // else{
        //     return redirect()->route('pengeluaran.laporan')->with('message', 'Data Tidak Ditemukan');
        //     //return back()->with('message', 'Data Tidak Ditemukan');
        // }
    }

    private function formatRp($angka){
        $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num];
    }
}
