<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    return response()->json(['message' => 'it works']);
});

Route::post('/register', [AuthController::class, 'register'])->name('user.create');
Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('user.logout');