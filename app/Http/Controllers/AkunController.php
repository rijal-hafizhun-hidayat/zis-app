<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index(){
        $setQueryAkun = $this->setQueryAkun();
        //dd($setQueryAkun->latest()->get());
        return Inertia::render('Akun/Index', [
            'akuns' => $setQueryAkun->latest()->get()
        ]);
    }

    public function create(){
        return Inertia::render('Akun/Create');
    }

    public function store(Request $request){
        $credential = $request->validate([
            'name'      => 'required|string',
            'username'  => 'required|string',
            'role'      => 'required|integer',
            'password'  => 'required|string'
       ]);
       $credential['password'] = Hash::make($request->password);
       Akun::create($credential);
       $response = [
        'status' => 201,
        'message' => 'berhasil membuat akun'
       ];
       return response()->json($response, 201);
    }

    public function show($id){
        return Inertia::render('Akun/Edit', [
            'akun' => Akun::find($id)
        ]);
    }

    public function update(Request $request, $id){
        $credential = $request->validate([
            'name'      => 'required|string',
            'username'  => 'required|string',
            'role'      => 'integer',
       ]);
       Akun::where('id', $id)->update($credential);
       return $this->responseApi(true, 'update data berhasil', 200);
    }

    public function destroy($id){
        $akun = Akun::find($id);
        if(!$akun->delete()){
            return $this->responseApi(false, 'akun tidak ditemukan', 404);
        }
        else{
            $akun->delete();
            return $this->responseApi(true, 'hapus data berhasil', 200);
        }
    }

    private function responseApi($status, $message, $code){
        $response = [
            'status' => $status,
            'message' => $message,
            'code' => $code
        ];
        return response()->json($response, $code);
    }

    private function setQueryAkun(){
        $queryAkun = DB::table('users');
        if(request()->filter){
            $queryAkun->where('name', 'like', '%'.request()->filter.'%');
        }

        return $queryAkun;
    }
}
