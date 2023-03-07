<?php
namespace App\Controllers\Admin;

class HomeController
{
     public function index()
     {
          ifNotAuth();
          return view('admin.home');
     }
     
}