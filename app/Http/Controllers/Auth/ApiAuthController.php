<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OtpGeneration;
use Bschmitt\Amqp\Facades\Amqp;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function register (RegisterRequest $request) {
        $user = User::create($request->all());
        $data['success'] = 1;
        $data['user'] = $user;
        $data['token'] =  $user->createToken('App')->accessToken;

        if($user){
            $otpGeneration = new OtpGeneration();
            $opt = $otpGeneration->generateOtp($user->id);
            $message = array('user' => $user->id,
                'otp' =>$opt,
                'email' =>$request->input('email'));
            Amqp::publish('ezKartOtpVerification', json_encode($message), ['queue' => 'ezKartOtpVerification']);

            return response()->json([
                'message' =>'user successfully created',
                'user' => $user
            ], 200);
        }
    }

    public function login (LoginRequest $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $data['success'] = 1;
            $data['user'] = $user;
            $data['token'] =  $user->createToken('App')->accessToken;
            return response()->json($data, '200');
        }
        else{
            return response()->json(['success' => '0', 'errors'=>'Invalid Credentials.'], 401);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->OauthAcessToken()->delete();
            $data['success'] = 1;
            $data['message'] = "Logged out successfully.";
            return response()->json($data, '200');
        }
    }

    public function checkAccess()
    {
        $user = $user = Auth::user();
        $response = array(
            "user" => $user,
            "status" => 200,
        );
        return response()->json($response);
    }


}
