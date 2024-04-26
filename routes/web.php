<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResidenteController;

//Rutas para reestablecer contraseña
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

// Rutas para el GET y retorno de vistas del login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/', [LoginController::class, 'showHome'])->middleware('auth');

// Rutas para el POST de las funciones de login y logout
Route::post('/login', [LoginController::class, 'login'])->name('loginNow');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas para residentes
Route::get('/Residentes/create', [ResidenteController::class, 'create'])->name('createRsdt');
Route::get('/Residentes/index', [ResidenteController::class, 'index'])->name('indexRsdt');
Route::get('/Residentes', [ResidenteController::class, 'showIndex'])->name('Residentes');
Route::post('/Residentes/store', [ResidenteController::class, 'store'])->name('storeRsdt');
