<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OtpGeneration;
use Bschmitt\Amqp\Facades\Amqp;




class AccountController extends Controller
{

    private $otpgeneration;

    public function __construct(OtpGeneration $otpgeneration)
    {
        $this->otpgeneration = $otpgeneration;
    }


    function createUser(Request $request){


        $user = new User();
        $user-> name = $request->input('name');
        $user-> password = $request->input('password');
        $user-> email = $request->input('email');
        $status = $user-> save();
        $userID = $user->id;


        if($status){
            $opt = $this->otpgeneration->generateOtp($userID);
            $message = array('user' => $userID,
                             'otp' =>$opt,
                             'email' =>$request->input('email'));


            Amqp::publish('ezKartOtpVerification', json_encode($message), ['queue' => 'ezKartOtpVerification']);
        }
        return ["res"=>"1"];
    }



    function removeUser(Request $request){

    }

    function updateUser(Request $request){

    }

    function blockUser(Request $request){
        $id = $request->input('id');
        return DB::table('users')
            ->where('id', $id)
            ->update(['user_status' => "3"]);


    }

    function passwordReset(Request $request){

        $id = $request->input('id');

    }
}
