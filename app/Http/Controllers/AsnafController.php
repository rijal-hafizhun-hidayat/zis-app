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
        return Inertia::render('Asnaf/Create');
    }

    public function store(Request $request){
        try {
            Asnaf::create($request->all());
            return $this->sendResponse(true, null, 'berhasil', 'berhasil tambah data', 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendResponse(false, null, null, $e->getMessage(), 404);
        }
    }

    public function sendResponse($status, $data, $title, $text, $code){
        $response = [
            'status' => $status,
            'data' => $data,
            'title' => $title,
            'text' => $text
        ];
        return response()->json($response, $code);
    }
}
