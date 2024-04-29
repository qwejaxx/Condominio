<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PlanificacionController;
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
Route::get('/Residentes/indexRep', [ResidenteController::class, 'getRepresentantes'])->name('indexRepRsdt');
Route::get('/Residentes/indexRoles', [ResidenteController::class, 'getRoles'])->name('indexRolesRsdt');
Route::get('/Residentes', [ResidenteController::class, 'showIndex'])->middleware('auth')->name('Residentes');
Route::get('/Residentes/index', [ResidenteController::class, 'index'])->name('indexRsdt');
Route::post('/Residentes/store', [ResidenteController::class, 'store'])->name('storeRsdt');
Route::get('/Residentes/{id}', [ResidenteController::class, 'show'])->name('showRsdt');
Route::put('/Residentes/{id}', [ResidenteController::class, 'update'])->name('updateRsdt');
Route::delete('/Residentes/{id}', [ResidenteController::class, 'destroy'])->name('destroyRsdt');

// Rutas para departamentos
Route::get('/Departamentos', [ResidenteController::class, 'showIndex'])->name('Departamentos');
Route::get('/Personal', [ResidenteController::class, 'showIndex'])->name('Personal');
Route::get('/Mascotas', [ResidenteController::class, 'showIndex'])->name('Mascotas');
Route::get('/Visitas', [ResidenteController::class, 'showIndex'])->name('Visitas');
Route::get('/Planificaciones', [ResidenteController::class, 'showIndex'])->name('Planificaciones');
Route::get('/Parking', [ResidenteController::class, 'showIndex'])->name('Parking');

// Rutas para personal de servicio
Route::get('/Personal/indexRoles', [PersonalController::class, 'getRoles'])->name('indexRolesPs');
Route::get('/Personal', [PersonalController::class, 'showIndex'])->middleware('auth')->name('Personal');
Route::get('/Personal/index', [PersonalController::class, 'index'])->name('indexPs');
Route::post('/Personal/store', [PersonalController::class, 'store'])->name('storePs');
Route::get('/Personal/{id}', [PersonalController::class, 'show'])->name('showPs');
Route::put('/Personal/{id}', [PersonalController::class, 'update'])->name('updatePs');
Route::delete('/Personal/{id}', [PersonalController::class, 'destroy'])->name('destroyPs');

// Rutas para Mascotas
Route::get('/Mascotas/indexRep', [MascotaController::class, 'getRepresentantes'])->name('indexRepMas');
Route::get('/Mascotas', [MascotaController::class, 'showIndex'])->middleware('auth')->name('Mascotas');
Route::get('/Mascotas/index', [MascotaController::class, 'index'])->name('indexMas');
Route::post('/Mascotas/store', [MascotaController::class, 'store'])->name('storeMas');
Route::get('/Mascotas/{id}', [MascotaController::class, 'show'])->name('showMas');
Route::put('/Mascotas/{id}', [MascotaController::class, 'update'])->name('updateMas');
Route::delete('/Mascotas/{id}', [MascotaController::class, 'destroy'])->name('destroyMas');

// Rutas para Planificaciones
Route::get('/Planificaciones', [PlanificacionController::class, 'showIndex'])->middleware('auth')->name('Planificaciones');
Route::get('/Planificaciones/index', [PlanificacionController::class, 'index'])->name('indexPlan');
Route::post('/Planificaciones/store', [PlanificacionController::class, 'store'])->name('storePlan');
Route::get('/Planificaciones/{id}', [PlanificacionController::class, 'show'])->name('showPlan');
Route::put('/Planificaciones/{id}', [PlanificacionController::class, 'update'])->name('updatePlan');
Route::delete('/Planificaciones/{id}', [PlanificacionController::class, 'destroy'])->name('destroyPlan');
