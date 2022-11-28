<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/11/22
 * Time: 11:56 AM
 */

namespace App\Services;

use App\Models\Otp;


class OtpGeneration
{

    function __construct() {
    }

    function generateOtp($userId){

        $six_digit_random_number = random_int(100000, 999999);

        $otp = new Otp();

        $otp ->user_id = $userId;
        $otp ->otp = $six_digit_random_number;
        $otp ->type = 'activation';
        $otp ->save();

        return $six_digit_random_number;
    }



}