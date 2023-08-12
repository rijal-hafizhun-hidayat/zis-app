<?php

namespace App\Http\Controllers;

use App\Models\Asnaf;
use App\Models\Infaq;
use App\Models\Pengeluaran;
use App\Models\Shadaqah;
use App\Models\Zakat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Dashboard/Index');
    }

    public function getSumZIS(){
        $response = [
            'status' => true,
            'zakat_fakir' => Asnaf::find(1)->total,
            'zakat_miskin' => Asnaf::find(2)->total,
            'zakat_amil' => Asnaf::find(3)->total,
            'zakat_muallaf' => Asnaf::find(4)->total,
            'zakat_riqab' => Asnaf::find(5)->total,
            'zakat_gharimin' => Asnaf::find(6)->total,
            'zakat_fisabilillah' => Asnaf::find(7)->total,
            'zakat_ibnu_sabil' => Asnaf::find(8)->total,
            'infaq' => Infaq::whereYear('created_at', date("Y"))->sum('nominal') - Pengeluaran::where([
                ['jenis_dana', 'Infaq'],
                ['confirmed', 1]
            ])->sum('nominal'),
            'shadaqah' => Shadaqah::whereYear('created_at', date("Y"))->sum('nominal') - Pengeluaran::where([
                ['jenis_dana', 'Shadaqah'],
                ['confirmed', 1]
            ])->sum('nominal'),
            'pengeluaran' => Pengeluaran::whereMonth('created_at', date('m'))->where('confirmed', 1)->sum('nominal'),
            'bulan' => $this->setMonth(date('n')),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getZakat(){
        $response = [
            'status' => true,
            'data' => Zakat::selectRaw('SUM(nominal) as total')->groupBy('bulan')->where('confirmed', 1)->oldest()->get(),
            'bulan' => Zakat::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getInfaq(){
        $response = [
            'status' => true,
            'data' => Infaq::selectRaw('SUM(nominal) as total')->groupBy('bulan')->where('confirmed', 1)->oldest()->get(),
            'bulan' => Infaq::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getShadaqah(){
        $response = [
            'status' => true,
            'data' => Shadaqah::selectRaw('SUM(nominal) as total')->groupBy('bulan')->where('confirmed', 1)->oldest()->get(),
            'bulan' => Shadaqah::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getPengeluaran(){
        $response = [
            'status' => true,
            'data' => Pengeluaran::selectRaw('SUM(nominal) as total')->groupBy('bulan')->where('confirmed', 1)->oldest()->get(),
            'bulan' => Pengeluaran::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    private function setMonth($num){
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $month[$num-1];
    }
}
