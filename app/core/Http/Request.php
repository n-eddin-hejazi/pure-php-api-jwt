<?php 
namespace App\Core\Http;

class Request
{
     public static function uri()
     {
          // $uri = trim($_SERVER['REQUEST_URI'], '/'); 
          $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
          $uri = str_replace(env('MAIN_URL'), '', $uri);
          
          $uri = trim($uri, '/');

          return $uri;
     }

     public static function get($key, $defaul = null)
     {
          return $_GET[$key] ?? $_POST[$key] ?? $defaul;
     }

     public static function method()
     {
          // return get or post
          return strtolower($_SERVER['REQUEST_METHOD']); 
     }

}