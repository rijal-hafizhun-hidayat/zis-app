<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getImageHome(){
        $response = [
            'status' => true,
            'data' => [
                'logo' => asset('images/Home/logo.jpg'),
                'jumbotron' => asset('images/Home/jumbotron.png'),
                'zakat' => asset('images/Home/zakat.png'),
                'infaq' => asset('images/Home/infaq.png'),
                'shadaqah' => asset('images/Home/shadaqah.png')
            ],
            'code' => 200
        ];

        return response()->json($response, 200);
    }

    public function getImageBuktiPembayaran($path, $image){
        $response = [
            'status' => true,
            'data' => asset('images/'.$path.'/'.$image),
            'code' => 200
        ];

        return response()->json($response, 200);
    }
}
