<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Login;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{

    public function _construct(){
        $this->middleware('auth:api', ['login']);
    }

    function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string'

        ]);

        if($validator->fails()){
            return response()->json($validator->error()->toJson(), 400);
        }

        if(!$token=auth()->attempt($validator->validated())){
            return response()->json([
                'error' =>'unauthorised',
            ], 401);
        }
        return response()->json([
            'access_token' =>$token,
            'type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL()*60,
            'user' => auth()->user()
        ]);

    }
}
