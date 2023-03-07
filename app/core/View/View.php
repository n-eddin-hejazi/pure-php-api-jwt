<?php
namespace App\Core\View;

class View
{
     public static function make($view_path) // tasks
     {
          // get the path of view directory
          $path = view_path();

          if(str_contains($view_path, '.')){
               // suppose that the parameter $view = 'admin.posts.index';
               $views = explode('.', $view_path);
               // $views = [
                    // 0 => 'admin',
                    // 1 => 'posts',
                    // 2 => 'index',
               // ];

               foreach($views as $view){
                    // admin is directory
                    // posts is directory
                    if(is_dir($path . $view)){
                         $path = $path . $view . "/"; 
                         // views/admin/
                         // views/admin/posts/
                    }
               }
               // get the last element in the array,
               // this element is file name
               // $view = views.admin.posts.index.view.php
               // dd($path . end($views) . '.view.php');
               return $view = $path . end($views) . '.view.php';
          }
          
          // dd($path . $view_path . '.view.php');
          // return view file name
          return  $view = $path . $view_path . '.view.php';     
     }
}