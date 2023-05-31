<?php

namespace App\Http\Controllers;

use App\Models\Sha;
use App\Models\Zakat;
//use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class ZakatLaporanController extends Controller
{
    public function index(){
        return Inertia::render('Zakat/Laporan/Index');
    }

    public function generateLaporan(Request $request){
        if($request->filled('bulan')){
            if(Zakat::where('bulan', $request->bulan)->exists()){
                $zakatFitrahUang = $this->formatRp((int)Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Fitrah'],
                    ['confirmed', 1]
                ])->sum('nominal'));
                $zakatFitrahBeras = Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Fitrah'],
                    ['confirmed', 1]
                ])->sum('berat_beras');
                $muzakkiFitrahUang = Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Fitrah'],
                    ['sha_id', '=', 2],
                    ['confirmed', 1]
                ])->count();
                $muzakkiFitrahBeras = Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Fitrah'],
                    ['sha_id', '=', 1],
                    ['confirmed', 1]
                ])->count();
                $zakatMaal = $this->formatRp(Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Maal'],
                    ['confirmed', 1]
                ])->sum('nominal'));
                $muzakkiZakatMaal = Zakat::where([
                    ['bulan', '=', $request->bulan],
                    ['jenis_zakat', '=', 'Zakat Maal'],
                    ['confirmed', 1]
                ])->count();
                $totalMuzakki = Zakat::where([
                    ['bulan', $request->bulan],
                    ['confirmed', 1]
                ])->count();
                $totalSaldoUang = $this->formatRp((int)Zakat::where([
                    ['bulan', $request->bulan],
                    ['confirmed', 1]
                ])->sum('nominal'));
                $totalSaldoBeras = $this->formatRp((int)Zakat::where([
                    ['bulan', $request->bulan],
                    ['confirmed', 1]
                ])->sum('berat_beras'));
            }
            else{
                return redirect()->route('zakat.laporan')->with('message', 'Data Tidak Ditemukan');
            }
        }
        else{
            $zakatFitrahUang = $this->formatRp((int)Zakat::where([
                ['jenis_zakat', 'Zakat Fitrah'],
                ['confirmed', 1]
            ])->sum('nominal'));
            $zakatFitrahBeras = Zakat::where([
                ['jenis_zakat', 'Zakat Fitrah'],
                ['confirmed', 1]
            ])->sum('berat_beras');
            $muzakkiFitrahUang = Zakat::where([
                ['jenis_zakat', '=', 'Zakat Fitrah'],
                ['sha_id', '=', 2],
                ['confirmed', 1]
            ])->count();
            $muzakkiFitrahBeras = Zakat::where([
                ['jenis_zakat', '=', 'Zakat Fitrah'],
                ['sha_id', '=', 1],
                ['confirmed', 1]
            ])->count();
            $zakatMaal = $this->formatRp((int)Zakat::where([
                ['jenis_zakat', 'Zakat Maal'],
                ['confirmed', 1]
            ])->sum('nominal'));
            $muzakkiZakatMaal = Zakat::where([
                ['jenis_zakat', 'Zakat Maal'],
                ['confirmed', 1]
            ])->count();
            $totalMuzakki = Zakat::where('confirmed', 1)->count();
            $totalSaldoUang = $this->formatRp((int)Zakat::where('confirmed', 1)->sum('nominal'));
            $totalSaldoBeras = Zakat::where('confirmed', 1)->sum('berat_beras');
        }
        $date = date("d").' '.$this->setMonth((int)date("m")-1).' '.date("Y");
        $bulan = $request->bulan;
        $pdf = PDF::loadview('report/Zakat/index', compact(
            'zakatFitrahUang',
            'zakatFitrahBeras',
            'muzakkiFitrahUang',
            'muzakkiFitrahBeras',
            'zakatMaal',
            'muzakkiZakatMaal',
            'totalMuzakki',
            'totalSaldoUang',
            'totalSaldoBeras',
            'date',
            'bulan'
        ));
        return $pdf->stream("laporan_zakat.pdf", array("Attachment" => false));
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
