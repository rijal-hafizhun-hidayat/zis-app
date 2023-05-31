<?php

namespace App\Http\Controllers;

use App\Models\Shadaqah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class ShadaqahLaporanController extends Controller
{
    public function index(){
        return Inertia::render('Shadaqah/Laporan/Index');
    }

    public function generateLaporan(Request $request){
        if($request->filled('bulan')){
            if(Shadaqah::where('bulan', $request->bulan)->exists()){
                $shadaqahUangs = Shadaqah::where([
                    ['bulan', $request->bulan],
                    ['confirmed', 1]
                ])->whereNotNull('nominal')->get();
                $shadaqahBarangs = Shadaqah::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_bantuan', '=', 'Barang'],
                    ['confirmed', 1]
                ])->get();
                $totalSaldo = $this->formatRp((int)Shadaqah::where([
                    ['bulan', $request->bulan],
                    ['confirmed', 1]
                ])->sum('nominal'));
            }
            else{
                return redirect()->route('shadaqah.laporan')->with('message', 'Data Tidak Ditemukan');
            }
        }
        else{
            $shadaqahUangs = Shadaqah::whereNotNull('nominal')->where('confirmed', 1)->get();
            $shadaqahBarangs = Shadaqah::where([
                ['jenis_bantuan', 'Barang'],
                ['confirmed', 1]
            ])->get();
            $totalSaldo = $this->formatRp((int)Shadaqah::where('confirmed', 1)->sum('nominal'));
        }

        $date = date("d").' '.$this->setMonth((int)date("m")-1).' '.date("Y");
        $bulan = $request->bulan;
        $pdf = PDF::loadview('report/Shadaqah/index', compact('shadaqahUangs', 'shadaqahBarangs', 'totalSaldo', 'date', 'bulan'));
        return $pdf->stream("laporan_shadaqah.pdf", array("Attachment" => false));
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
