<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtletaController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/atletas/create', [AtletaController::class, 'create'])->name('atletas.create');
Route::get('/atletas/buscar', [AtletaController::class, 'buscar'])->name('atletas.buscar');
Route::get('/atletas/buscar-cpf', [AtletaController::class, 'buscarPorCpf'])->name('atletas.buscar-cpf');
Route::get('/atletas', [AtletaController::class, 'index'])->name('atletas.index'); 
Route::get('/atletas/{id}', [AtletaController::class, 'show'])->name('atletas.show'); 
Route::post('/atletas', [AtletaController::class, 'store'])->name('atletas.store'); 
Route::put('/atletas/{id}', [AtletaController::class, 'update'])->name('atletas.update');
Route::delete('/atletas/{id}', [AtletaController::class, 'destroy'])->name('atletas.destroy');


