<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index(){
        return Inertia::render('Login/Index');
    }

    public function authenticate(Request $request){
        //dd($request->all());
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $request->session()->put('isLogin', true);
            $request->session()->put('role', Auth::user()->role);
            //dd(Auth::user()->role);

            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'login berhasil'
            ];

            return response()->json($response, 200);
        }

        return response()->json(false, 404);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
 
        $request->session()->regenerateToken();

        return response()->json(true, 404);
    }
}
