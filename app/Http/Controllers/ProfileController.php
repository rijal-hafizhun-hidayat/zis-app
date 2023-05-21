<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use PDF;

class ProfileController extends Controller
{
    public function index(){
        return Inertia::render('Profile/Index');
    }

    public function getUsername(){
        //dd(Auth::user()->name);
        $response = [
            'username' => Auth::user()->username
        ];

        return response()->json($response, 200);
    }

    public function updateUsername(Request $request){
        $request->validate([
            'username' => 'required|string',
            'newUsername' => 'required|string'
        ]);

        $profile = Profile::find(Auth::id());
 
        $profile->username = $request->newUsername;
        
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'username berhasil di ubah',
            'data' => $profile->username 
        ], 200);
    }

    public function updatePassword(Request $request){
        $request->validate([
            'newPass' => 'required|string',
            'konfPass' => 'required|string'
        ]);

        if (Hash::check($request->konfPass, Auth::user()->password)) {
            $new = Hash::make($request->newPass);

            DB::table('users')->where('id', Auth::id())->update(['password' => $new]);

            $response = [
                'status' => true,
                'message' => 'berhasil update password akun'
            ];

            return response()->json($response, 200);
        }

        $response = [
            'status' => false,
            'message' => 'gagal update password akun',
            'code' => 400
        ];

        return response()->json($response, 400);
    }
}
