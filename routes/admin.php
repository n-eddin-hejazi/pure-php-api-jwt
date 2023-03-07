<?php
use App\Core\Http\Route;
use App\Controllers\Admin\HomeController as AdminHomeController;

    // admin dashboard route
    Route::get('admin', [AdminHomeController::class, 'index']);