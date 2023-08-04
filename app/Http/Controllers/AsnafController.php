<?php

namespace App\Http\Controllers;

use App\Models\Asnaf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AsnafController extends Controller
{
    public function index(){
        return Inertia::render('Asnaf/Index', [
            'asnafs' => Asnaf::all()
        ]);
    }

    public function create(){
        // var_dump(true);
        //dd(true);
        return Inertia::render('Asnaf/Create');
    }

    public function show($id){
        //dd(false);
        return Inertia::render('Asnaf/Edit', [
            'id' => $id
        ]);
    }

    public function getAsnafById($id){
        try {
            $asnaf = Asnaf::find($id);
            return $this->sendResponse(true, $asnaf, null, null, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendResponse(false, null, null, $e->getMessage(), 404);
        }
    }

    public function store(Request $request){
        try {
            Asnaf::create($request->all());
            return $this->sendResponse(true, null, 'berhasil', 'berhasil tambah data', 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendResponse(false, null, null, $e->getMessage(), 404);
        }
    }

    public function destroy($id){
        try {
            Asnaf::destroy($id);
            return $this->sendResponse(true, null, 'berhasil', 'berhasil hapus data', 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendResponse(false, null, null, $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id){
       try {
            Asnaf::where('id', $id)->update($request->all());
            return $this->sendResponse(true, null, 'berhasil', 'berhasil update data', 200);
       } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendResponse(false, null, null, $e->getMessage(), 404);
       }
    }

    private function sendResponse($status, $data, $title, $text, $code){
        $response = [
            'status' => $status,
            'data' => $data,
            'title' => $title,
            'text' => $text
        ];
        return response()->json($response, $code);
    }
}
