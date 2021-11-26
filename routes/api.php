<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/request-for-otp', [AuthenticationController::class, 'generateOtp']);
Route::post('/registration', [AuthenticationController::class, 'registration']);

Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::post('/logout', [AuthenticationController::class, 'logout']);

  Route::prefix('/issue')->group(function () {
    Route::get('/', [ApiController::class, 'issueList']);
    Route::post('/create', [ApiController::class, 'issueCreate']);
  });
});
