<?php
use App\Core\Http\Route;
use App\Controllers\Api\RegisterController;


    Route::get('api/register', [RegisterController::class, 'register']);