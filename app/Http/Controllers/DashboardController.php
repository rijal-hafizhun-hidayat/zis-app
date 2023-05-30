<?php

namespace App\Http\Controllers;

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
            'zakat' => Zakat::whereYear('created_at', date("Y"))->sum('nominal') - Pengeluaran::where('jenis_dana', 'Zakat')->sum('nominal'),
            'infaq' => Infaq::whereYear('created_at', date("Y"))->sum('nominal') - Pengeluaran::where('jenis_dana', 'Infaq')->sum('nominal'),
            'shadaqah' => Shadaqah::whereYear('created_at', date("Y"))->sum('nominal') - Pengeluaran::where('jenis_dana', 'Shadaqah')->sum('nominal'),
            'pengeluaran' => Pengeluaran::whereDate('created_at', date("Y-m-d"))->sum('nominal'),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getZakat(){
        $response = [
            'status' => true,
            'data' => Zakat::selectRaw('SUM(nominal) as total')->groupBy('bulan')->oldest()->get(),
            'bulan' => Zakat::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getInfaq(){
        $response = [
            'status' => true,
            'data' => Infaq::selectRaw('SUM(nominal) as total')->groupBy('bulan')->oldest()->get(),
            'bulan' => Infaq::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getShadaqah(){
        $response = [
            'status' => true,
            'data' => Shadaqah::selectRaw('SUM(nominal) as total')->groupBy('bulan')->oldest()->get(),
            'bulan' => Shadaqah::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }

    public function getPengeluaran(){
        $response = [
            'status' => true,
            'data' => Pengeluaran::selectRaw('SUM(nominal) as total')->groupBy('bulan')->oldest()->get(),
            'bulan' => Pengeluaran::select('bulan')->distinct()->oldest()->get(),
            'code' => 200
        ];
        return response()->json($response, 200);
    }
}
