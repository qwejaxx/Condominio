<?php

use App\Http\Controllers\AdquisicionController;
use App\Http\Controllers\DepartamentoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ParqueoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PlanificacionController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\AsignacionPlanController;

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
Route::get('/Departamentos/indexRepresentantes', [DepartamentoController::class, 'getRepresentantes'])->name('indexRepDpto');
Route::get('/Departamentos/indexPark', [DepartamentoController::class, 'getParqueos'])->name('indexParkDpto');
Route::get('/Departamentos', [DepartamentoController::class, 'showIndex'])->middleware('auth')->name('Departamentos');
Route::get('/Departamentos/index', [DepartamentoController::class, 'index'])->name('indexDpto');
Route::post('/Departamentos/store', [DepartamentoController::class, 'store'])->name('storeDpto');
Route::get('/Departamentos/{id}', [DepartamentoController::class, 'show'])->name('showDpto');
Route::put('/Departamentos/{id}', [DepartamentoController::class, 'update'])->name('updateDpto');
Route::delete('/Departamentos/{id}', [DepartamentoController::class, 'destroy'])->name('destroyDpto');

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

// Rutas para Visitas
Route::get('/Visitas/indexRes', [VisitanteController::class, 'getResidentes'])->name('indexResVst');
Route::get('/Visitas', [VisitanteController::class, 'showIndex'])->middleware('auth')->name('Visitas');
Route::get('/Visitas/index', [VisitanteController::class, 'index'])->name('indexVst');
Route::post('/Visitas/store', [VisitanteController::class, 'store'])->name('storeVst');
Route::get('/Visitas/{id}', [VisitanteController::class, 'show'])->name('showVst');
Route::put('/Visitas/{id}', [VisitanteController::class, 'update'])->name('updateVst');
Route::delete('/Visitas/{id}', [VisitanteController::class, 'destroy'])->name('destroyVst');

// Rutas para Parking
Route::get('/Parking', [ParqueoController::class, 'showIndex'])->middleware('auth')->name('Parking');
Route::get('/Parking/index', [ParqueoController::class, 'index'])->name('indexPar');
Route::post('/Parking/store', [ParqueoController::class, 'store'])->name('storePar');
Route::get('/Parking/{id}', [ParqueoController::class, 'show'])->name('showPar');
Route::put('/Parking/{id}', [ParqueoController::class, 'update'])->name('updatePar');
Route::delete('/Parking/{id}', [ParqueoController::class, 'destroy'])->name('destroyPar');

// Rutas para Asignacion Plan
Route::get('/Planificaciones/NoAsignaciones/{id}', [AsignacionPlanController::class, 'getNoParticipantes'])->name('indexNoPartPA');
Route::get('/Planificaciones/Asignaciones/{id}', [AsignacionPlanController::class, 'getParticipantes'])->name('indexPartPA');
Route::get('/Planificaciones/Asignaciones', [AsignacionPlanController::class, 'showIndex'])->name('Asignaciones');
Route::post('/Asignaciones/store', [AsignacionPlanController::class, 'storeParticipantes'])->name('storeParticipantes');
Route::post('/Asignaciones/update', [AsignacionPlanController::class, 'updateAsignaciones'])->name('updateParticipantes');

// Rutas para asignaciones
Route::get('/Adquisiciones', [AdquisicionController::class, 'showIndex'])->name('Adquisiciones');
Route::get('/Adquisiciones/index', [AdquisicionController::class, 'index'])->name('indexAdq');
Route::get('/Adquisiciones/getDptos', [AdquisicionController::class, 'getDepartamentos'])->name('getDptos');
Route::get('/Adquisiciones/getRep', [AdquisicionController::class, 'getRepresentantes'])->name('getRep');
Route::post('/Adquisiciones/store', [AdquisicionController::class, 'store'])->name('storeAdq');
Route::get('/Adquisiciones/{id}', [AdquisicionController::class, 'show'])->name('showAdq');
Route::put('/Adquisiciones/{id}', [AdquisicionController::class, 'update'])->name('updateAdq');
Route::delete('/Adquisiciones/{id}', [AdquisicionController::class, 'destroy'])->name('destroyAdq');
