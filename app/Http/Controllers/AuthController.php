<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index(Request $request){
        // Auth::logout();
        $request->session()->invalidate();
        // request()->session()->regenerateToken();
        return Inertia::render('Login/Index');
    }

    public function authenticate(Request $request){
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $request->session()->put('isLogin', true);
            $request->session()->put('role', Auth::user()->role);
            $request->session()->put('name', Auth::user()->name);
            return $this->responseApi(true, 'berhasil', 'selamat datang', 200);
        }
        else{
            return $this->responseApi(false, 'gagal', 'username atau password salah', 404);
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->responseApi(true, 'berhasil', 'berhasil logout', 200);
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
}
