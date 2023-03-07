<?php
use App\Core\Http\Route;
use App\Controllers\Api\RegisterController;
use App\Controllers\Api\LoginController;


    Route::get('api/register', [RegisterController::class, 'register']);
    Route::get('api/login', [LoginController::class, 'login']);
