<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        return Inertia::render('Home/Index');
    }

    public function getImageHome(){
        $response = [
            'status' => true,
            'data' => [
                'logo' => asset('storage/images/Home/logo.jpg'),
                'jumbotron' => asset('storage/images/Home/jumbotron.png'),
                'zakat' => asset('storage/images/Home/zakat.png'),
                'infaq' => asset('storage/images/Home/infaq.png'),
                'shadaqah' => asset('storage/images/Home/shadaqah.png')
            ],
            'code' => 200
        ];
        return response()->json($response, 200);
    }
}
