<?php

namespace App\Http\Controllers;

use App\Models\Sha;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShaController extends Controller
{
    public function index(){
        return Inertia::render('Sha/Index', [
            'shas' => Sha::select('id', 'nama','harga')->get()
        ]);
    }

    public function create(){
        return Inertia::render('Sha/Create');
    }

    public function store(Request $request){
        $credentials = $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric'
        ]);

        Sha::create($credentials);

        $response = [
            'status' => true,
            'message' => 'simpan data berhasil',
            'code' => 200
        ];

        return response()->json($response, 200);

    }

    public function show($id){
        return Inertia::render('Sha/Edit', [
            'sha' => Sha::find($id)
        ]);
    }

    public function update(Request $request, $id){
        $credentials = $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric'
        ]);

        Sha::where('id', $id)->update($credentials);

        $response = [
            'status' => true,
            'message' => 'update data berhasil',
            'code' => 200
        ];

        return response()->json($response, 200);
    }

    public function destroy($id){
        Sha::destroy($id);

        $response = [
            'status' => true,
            'message' => 'hapus berhasil',
            'code' => 200
        ];

        return response()->json($response, 200);
    }
}
