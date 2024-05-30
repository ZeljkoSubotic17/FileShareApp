<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/register-invited-user', function (Request $request) {
    $token = Request::query('token');
    return view('auth.registerInvitedUser', ['token' => $token]);
});

Route::post('/register-invited-user', [RegisterController::class, 'registerInvitedUser'])->name('register-invited-user');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('documents', DocumentController::class);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/users', [UserController::class, 'index'])->name('users');
});

Auth::routes(['verify' => true]);
