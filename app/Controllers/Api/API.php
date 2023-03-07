<?php
namespace App\Controllers\Api;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

abstract class API
{   
    protected $mobile_secret = 'aAce3324ASDd21*643!#FDHY7546w`zasdl;2le"2ewe1!a#-+';
    private static $secret_key = 'ASDFLESKF#@$@#RSDLK#@!$#@%T#$43243213!SDASDASD!12312SADASDQWe232';
    private static $expiration_time_of_jwt_token = 31536000; // one year

    public function __construct()
    {
        header("Content-type: application/json");
    }

    public static function generalValidation()
    {
        // if(!self::isHttps()){
        //     $message = "Forbidden - HTTP not allowed";
        //     self::response([], $message, FALSE, 403);
        // }

        if(self::method() !== 'post'){
            $message = 'Just POST Method is Allowed';
            self::response([], $message, FALSE, 405);
        }
    }
}