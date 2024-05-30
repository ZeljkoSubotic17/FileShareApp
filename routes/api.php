<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\InviteController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('documents', DocumentController::class);

    Route::post('invite', [InviteController::class, 'sendInvite']);

    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::put('/users/{user}', [UserController::class, 'update']);
});
