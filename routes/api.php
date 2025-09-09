<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\Auth\PasswardResetController;
use App\Http\Controllers\API\V1\Student\ProfileContoller;
Route::post('/users', [UserController::class, 'store'])->middleware(['api', 'auth:sanctum', 'role:admin']);
Route::post('edit-user/{id}',[UserController::class, 'edit'])->middleware(['api', 'auth:sanctum', 'role:admin']);
Route::post('delete-user/{id}',[UserController::class, 'delete'])->middleware(['api', 'auth:sanctum', 'role:admin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout/{id}', [AuthController::class, 'logout'])->middleware(['api', 'auth:sanctum']);
Route::post('/forget-password', [PasswardResetController::class, 'forget']);
Route::post('/reset-password', [PasswardResetController::class, 'reset']);
Route::post('/edit-profile/{id}', [ProfileContoller::class, 'update'])->middleware(['api', 'auth:sanctum', 'role:student']);