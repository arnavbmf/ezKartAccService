<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ThirdPartyApiController extends Controller
{
    // public function createOrUpdateProduct(Request $request) {
    //     $user = Auth::user()->toArray();
    //     $product = $request->toArray();
    //     $product['user'] = $user;
    //     $response = Http::post('http://'.env("PRODUCT_SERVICE_IP_ADDRESS").'/createOrUpdateProduct',$product);
    //     $jsonData = $response->json();
    //     dd($response);
    // }

    public function listProducts(Request $request) {
        $response = Http::get('http://'.env("PRODUCT_SERVICE_IP_ADDRESS").'/listProducts');
        $jsonData = $response->json();
        return response()->json($jsonData);
    }

    public function getProduct($productId) {
        $response = Http::get('http://'.env("PRODUCT_SERVICE_IP_ADDRESS").'/getProduct/'.$productId);
        $jsonData = $response->json();
        return response()->json($jsonData);
    }
}
