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

    public static function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function isHttps()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            return true;
        }
        
        return false;
    }

    public function request()
    {
        $request = $_REQUEST;
        // file_get_contents('php://input'); is a PHP function call that retrieves the raw request body sent in an HTTP request
        // In general, when data is sent in an HTTP request, it can be sent in different formats, such as application/json, application/x-www-form-urlencoded, multipart/form-data, or others. When data is sent in application/json format, it is typically sent in the request body as a raw JSON string.
        // The file_get_contents() function in PHP allows you to read the contents of a file into a string. In the case of HTTP requests, the php://input stream is a special read-only stream that allows you to read the raw request body.
        $data = json_decode(file_get_contents('php://input'), true);
        if(is_array($data)){
            $request += $data;
        }
         return  (object)$request;
    }

    public static function response(array $data = [], string $errorMessage = NULL, bool $done = TRUE, int $statusCode = 200, bool $token = FALSE)
    {
        http_response_code($statusCode);
        if($token){
            $data +=['Token' => self::generateToken()];
        }
        $response = [
            'data' => $data,
            'errorMessage' => $errorMessage,
            'done' => $done,
            'statusCode' => $statusCode
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }

}