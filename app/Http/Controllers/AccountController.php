<?php

namespace App\Http\Controllers;

//use Dotenv\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\OtpGeneration;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;




class AccountController extends Controller
{


    public function __construct()
    {
    }


    function createUser(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|string',
            'password' => 'required|string',
            'role' => 'required',

        ]);

        if($validator->fails()){
            return response()->json($validator->error()->toJson(), 422);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->input('password'))]
        ));

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

        return response()->json([
            'message' =>'Error! User not created',
                 'user' => $user
            ], 500);


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

        DB::transaction(function($userId, $otp) {

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
        });

        return ["res"=>"1"];

    }

    function fetchUser(Request $request){

        $user = DB::table('users')
            ->where('id', $request->input('id'))
            ->get();

        return $user;



    }


}
