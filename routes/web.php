<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

//define controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\ShaController;
use App\Http\Controllers\InfaqController;
use App\Http\Controllers\InfaqLaporanController;
use App\Http\Controllers\ShadaqahController;
use App\Http\Controllers\ShadaqahLaporanController;
use App\Http\Controllers\ZakatController;
use App\Http\Controllers\ZakatLaporanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengeluaranLaporanController;
use App\Http\Controllers\DonasiController;
use App\Models\Infaq;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //dd(Hash::make('admin'));
    return Inertia::render('Home/Index');
});

Route::get('/login', function () {
    //dd(Hash::make('admin'));
    return Inertia::render('Login/Index');
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])->name('auth');


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['isLogin'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('Profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    //AKUN
    Route::middleware(['isAdmin'])->group(function(){

        Route::get('/akun', [AkunController::class, 'index'])->name('akun');
        Route::get('/akun/add', [AkunController::class, 'create'])->name('akun.create');
        Route::get('/akun/{id}', [AkunController::class, 'show'])->name('akun.show');

        //API AKUN
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');
        Route::put('/akun/{id}', [AkunController::class, 'update'])->name('akun.update');
    });

    //SHA
    Route::get('/sha', [ShaController::class, 'index'])->name('sha');
    Route::get('/sha/add', [ShaController::class, 'create'])->name('sha.create');
    Route::get('/sha/{id}', [ShaController::class, 'show'])->name('sha.show');

    //INFAQ
    Route::get('/infaq', [InfaqController::class, 'index'])->name('infaq');
    Route::get('/infaq/add', [InfaqController::class, 'create'])->name('infaq.create');
    Route::get('/infaq/{id}', [InfaqController::class, 'show'])->name('infaq.show');

    //INFAQ LAPORAN
    Route::get('/laporan/infaq', [InfaqLaporanController::class, 'index'])->name('infaq.laporan');
    Route::post('/laporan/infaq', [InfaqLaporanController::class, 'generateLaporan'])->name('infaq.generateLaporan');

    //SHADAQAH
    Route::get('/shadaqah', [ShadaqahController::class, 'index'])->name('shadaqah');
    Route::get('/shadaqah/add', [ShadaqahController::class, 'create'])->name('shadaqah.create');
    Route::get('/shadaqah/{id}', [ShadaqahController::class, 'show'])->name('shadaqah.show');

    //SHADAQAH LAPORAN
    Route::get('/laporan/shadaqah', [ShadaqahLaporanController::class, 'index'])->name('shadaqah.laporan');
    Route::post('/laporan/shadaqah', [ShadaqahLaporanController::class, 'generateLaporan'])->name('shadaqah.generateLaporan');

    //ZAKAT
    Route::get('/zakat', [ZakatController::class, 'index'])->name('zakat');
    Route::get('/zakat/add', [ZakatController::class, 'create'])->name('zakat.create');
    Route::get('/zakat/{id}', [ZakatController::class, 'show'])->name('zakat.show');
    Route::get('/zakat/showImage/{image}', [ZakatController::class, 'showImage'])->name('zakat.showImage');
    Route::get('/report', [ZakatController::class, 'exportPdf'])->name('zakat.exportPdf');

    //ZAKAT LAPORAN
    Route::get('/laporan/zakat', [ZakatLaporanController::class, 'index'])->name('zakat.laporan');
    Route::post('/laporan/zakat', [ZakatLaporanController::class, 'generateLaporan'])->name('zakat.generateLaporan');

    //PENGELUARAN
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::get('/pengeluaran/add', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::get('/pengeluaran/{id}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');

    //PENGELUARAN LAPORAN
    Route::get('/laporan/pengeluaran', [PengeluaranLaporanController::class, 'index'])->name('pengeluaran.laporan');
    Route::post('/laporan/pengeluaran', [PengeluaranLaporanController::class, 'generateLaporan'])->name('pengeluaran.generateLaporan');

    //API ROUTE

    //API DASHBOARD
    Route::get('/getSumZIS', [DashboardController::class, 'getSumZIS'])->name('getSumZIS');
    Route::get('/getZakat', [DashboardController::class, 'getZakat'])->name('getZakat');
    Route::get('/getInfaq', [DashboardController::class, 'getInfaq'])->name('getInfaq');
    Route::get('/getShadaqah', [DashboardController::class, 'getShadaqah'])->name('getShadaqah');
    Route::get('/getPengeluaran', [DashboardController::class, 'getPengeluaran'])->name('getPengeluaran');

    //API PROFILE
    Route::get('/getUsername', [ProfileController::class, 'getUsername'])->name('getUsername');
    Route::get('/getPassword', [ProfileController::class, 'getPassword'])->name('getPassword');
    Route::put('/updateUsername', [ProfileController::class, 'updateUsername'])->name('updateUsername');
    Route::put('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');

    //API SHA
    Route::post('/sha', [ShaController::class, 'store'])->name('sha.store');
    Route::delete('/sha/{id}', [ShaController::class, 'destroy'])->name('sha.destroy');
    Route::put('/sha/{id}', [ShaController::class, 'update'])->name('sha.update');

    //API INFAQ
    Route::post('/infaq', [InfaqController::class, 'store'])->name('infaq.store');
    Route::delete('/infaq/{id}', [InfaqController::class, 'delete'])->name('infaq.destroy');
    Route::put('/infaq/{id}', [InfaqController::class, 'update'])->name('infaq.update');
    Route::put('infaq/confirmed/{id}', [InfaqController::class, 'confirmed'])->name('infaq.confirmed');

    //API SHADAQAH
    Route::post('/shadaqah', [ShadaqahController::class, 'store'])->name('shadaqah.store');
    Route::delete('/shadaqah/{id}', [ShadaqahController::class, 'destroy'])->name('shadaqah.destroy');
    Route::put('/shadaqah/{id}', [ShadaqahController::class, 'update'])->name('shadaqah.update');
    Route::put('/shadaqah/confirmed/{id}', [ShadaqahController::class, 'confirmed'])->name('shadaqah.confirmed');

    //API ZAKAT
    Route::post('/zakat', [ZakatController::class, 'store'])->name('zakat.store');
    Route::delete('/zakat/{id}', [ZakatController::class, 'destroy'])->name('zakat.destroy');
    Route::put('/zakat/{id}', [ZakatController::class, 'update'])->name('zakat.update');
    Route::put('/zakat/confirmed/{id}', [ZakatController::class, 'confirmed'])->name('zakat.confirmed');
    Route::get('/getNominalSha/{id}', [ZakatController::class, 'getNominalSha'])->name('getNominalSha');

    //API PENGELUARAN
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::put('/pengeluaran/confirmed/{id}', [PengeluaranController::class, 'confirmed'])->name('pengeluaran.confirmed');
});

//DONASI
Route::get('/donasi', [DonasiController::class, 'index'])->name('donasi');

//API DONASI
Route::get('/getDonasi', [DonasiController::class, 'getDonasi'])->name('getDonasi');
Route::post('/donasi', [DonasiController::class, 'store'])->name('donasi.store');
Route::get('/getSatuan', [DonasiController::class, 'getSatuan'])->name('getSatuan');
Route::get('/getNominal/{id}', [DonasiController::class, 'getNominal'])->name('getNominal');
//Auth::routes();