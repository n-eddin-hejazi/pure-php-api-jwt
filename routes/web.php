<?php 
use App\Core\Http\Route;
use App\Controllers\Auth\RegisterController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\LogoutController;
use App\Controllers\Auth\ForgetPasswordController;
use App\Controllers\Auth\ResetPasswordController;
use App\Controllers\HomeController;

     // homae page route
     Route::get('', [HomeController::class, 'home']);
     Route::get('404', [HomeController::class, 'notFound']);

     // register page routes
     Route::get('register', [RegisterController::class, 'index']);
     Route::post('register', [RegisterController::class, 'store']);

     // login page routes
     Route::get('login', [LoginController::class, 'index']);
     Route::post('login', [LoginController::class, 'store']);

     // logout page route
     Route::get('logout', [LogoutController::class, 'index']);

     // forget password page routes
     Route::get('forget-password', [ForgetPasswordController::class, 'index']);
     Route::post('forget-password', [ForgetPasswordController::class, 'forgetPassword']);

     // reset password page routes
     Route::get('reset-password', [ResetPasswordController::class, 'index']);
     Route::post('reset/password', [ResetPasswordController::class, 'resetPassword']);

     


     
