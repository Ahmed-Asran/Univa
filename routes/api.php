<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Auth\AuthController;
Route::post('/users', [UserController::class, 'store'])->middleware(['api', 'auth:sanctum', 'role:admin']);
Route::post('/login', [AuthController::class, 'login']);