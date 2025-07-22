<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ServiceCategoryController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return UserResource::make($request->user());
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user', [UserController::class, 'update']);

    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('service-categories', ServiceCategoryController::class);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'me']);
});