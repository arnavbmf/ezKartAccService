<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OtpGeneration;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\DB;





class AccountController extends Controller
{


    public function __construct()
    {
    }


    function createUser(Request $request){


        $user = new User();
        $otpGeneration = new OtpGeneration();
        $user-> name = $request->input('name');
        $user-> password = $request->input('password');
        $user-> email = $request->input('email');
        $status = $user-> save();
        $userID = $user->id;


        if($status){
            $opt = $otpGeneration->generateOtp($userID);
            $message = array('user' => $userID,
                             'otp' =>$opt,
                             'email' =>$request->input('email'));


            Amqp::publish('ezKartOtpVerification', json_encode($message), ['queue' => 'ezKartOtpVerification']);
        }
        return ["res"=>"1"];
    }



    function removeUser(Request $request){
        $id = $request->input('id');

        $user = DB::table('users')
            ->where('id', $id);

        $user->delete();
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

    function validateUserAcc($userId, $otp){

        $user = DB::table('otps')
            ->where('otp', $otp)
            ->where('user_id', $userId)
            ->where('type', 'activation')
            ->where('otp_status', '0')
            ->get();
        if($user->count()==1){
            DB::table('otps')
                ->where('otp', $otp)
                ->where('user_id', $userId)
                ->update(['otp_status' => "1"]);

            DB::table('users')
                ->where('id', $userId)
                ->update(['user_status' => "1", 'email_verified_at' =>date('Y-m-d H:i:s')]);

        }

    }
}
