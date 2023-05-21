<?php

namespace App\Http\Controllers;

use App\Models\Infaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class InfaqLaporanController extends Controller
{
    public function index(){
        return Inertia::render('Infaq/Laporan/Index');
    }

    public function generateLaporan(Request $request){
        if($request->filled('bulan')){
            if(Infaq::where('bulan', $request->bulan)->exists()){
                $infaqs = Infaq::where('bulan', $request->bulan)->get();
                $totalInfaq = $this->formatRp((int)Infaq::where('bulan', $request->bulan)->sum('nominal'));
            }
            else{
                return redirect()->route('infaq.laporan')->with('message', 'Data Tidak Ditemukan');
            }
        }
        else{
            $infaqs = Infaq::get();
            $totalInfaq = $this->formatRp((int)Infaq::sum('nominal'));
        }

        $date = date("d").' '.$this->setMonth((int)date("m")-1).' '.date("Y");
        $bulan = $request->bulan;

        $pdf = PDF::loadview('report/Infaq/index', compact('infaqs', 'totalInfaq', 'date', 'bulan'));
    	
        return $pdf->stream("laporan_infaq.pdf", array("Attachment" => false));
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
