<?php

namespace App\Controllers;
class HomeController
{
     public function home()
     {
          return view('home');
     }

     public function notFound()
     {
          return view('404');
     }
}