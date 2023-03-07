<?php
namespace App\Core\Http;
use App\Core\Http\Request;

class Route
{
     public static array $get = [];
     public static array $post = [];

     public static function get($uri, $action)
     {
          self::$get[$uri] = $action;
     }

     public static function post($uri, $action)
     {
          self::$post[$uri] = $action;
     }

     public function resolve()
     {
          $uri = Request::uri();
          $method = Request::method();
               
          if(array_key_exists($uri, self::$get)){
               $action = self::$get[$uri];

               if(is_array($action)){
                    $controller_name = $action[0];
                    $method_name = $action[1];
                    $this->callAction($controller_name, $method_name);
               }

               if(is_callable($action)){
                    call_user_func_array($action, []);
               }
               
               
          }

          if(array_key_exists($uri, self::$post)){
               $action = self::$post[$uri];

               if(is_array($action)){
                    $controller_name = $action[0];
                    $method_name = $action[1];
                    $this->callAction($controller_name, $method_name);
               }

               if(is_callable($action)){
                    call_user_func_array($action, []);
               }
          }

          // not found page
          if((!array_key_exists($uri, self::$get)) && (!array_key_exists($uri, self::$post))){
               return view('404');
          }
          
     }

     private function callAction($controller_name, $method_name)
     {
          $controller = new $controller_name;
          $action = $controller->{$method_name}();
     }

}