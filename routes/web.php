<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtletaController;
use App\Http\Controllers\AdminAuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// rota de login
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// submissão do formulário de login
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

// área protegida de administração
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/atletas/create', [AtletaController::class, 'create'])->name('atletas.create');
    Route::get('/atletas/buscar-cpf', [AtletaController::class, 'buscarPorCpf'])->name('atletas.buscar-cpf');
    Route::post('/atletas', [AtletaController::class, 'store'])->name('atletas.store'); 
    Route::put('/atletas/{id}', [AtletaController::class, 'update'])->name('atletas.update');
    Route::delete('/atletas/{id}', [AtletaController::class, 'destroy'])->name('atletas.destroy');    
});

Route::get('/atletas/buscar', [AtletaController::class, 'buscar'])->name('atletas.buscar');
Route::get('/atletas/{id}', [AtletaController::class, 'show'])->name('atletas.show'); 
Route::get('/atletas', [AtletaController::class, 'index'])->name('atletas.index'); 
Route::post('/atleta/visualizar/{id}', [AtletaController::class, 'registrarVisualizacao']);


