<?php
use App\Core\Http\Route;
use App\Controllers\Api\RegisterController;
use App\Controllers\Api\LoginController;
use App\Controllers\Api\UserController;
use App\Controllers\Api\ForgetPasswordController;

    Route::get('api/register', [RegisterController::class, 'register']);
    Route::get('api/login', [LoginController::class, 'login']);
    Route::get('api/users', [UserController::class, 'users']);
    Route::get('api/forget-password', [ForgetPasswordController::class, 'forgetPassword']);

