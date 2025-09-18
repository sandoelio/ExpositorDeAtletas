<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtletaController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAtletaController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// rota de login
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// submissão do formulário de login
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

Route::post('/logout', [App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');

// área protegida de administração
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/atletas/template', [AtletaController::class, 'downloadTemplate'])->name('atletas.template');
    Route::get('/atletas/importar',[AtletaController::class, 'showImportForm'])->name('atletas.import.form');
    Route::post('/atletas/importar',[AtletaController::class, 'import'])->name('atletas.import');
    Route::get('/atletas/create', [AtletaController::class, 'create'])->name('atletas.create');
    Route::post('/atletas', [AtletaController::class, 'store'])->name('atletas.store');
    Route::get('/atletas/{id}/edit', [AtletaController::class, 'edit'])->name('atletas.edit');
    Route::put('/atletas/{id}', [AtletaController::class, 'update'])->name('atletas.update');
    Route::delete('/atletas/{id}', [AtletaController::class, 'destroy'])->name('atletas.destroy'); 
    Route::get('/dashboard', function() {return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/atletas', [AdminAtletaController::class, 'index'])->name('admin.index');   
});

Route::get('/atletas/buscar', [AtletaController::class, 'buscar'])->name('atletas.buscar');
Route::get('/atletas/{id}', [AtletaController::class, 'show'])->name('atletas.show'); 
Route::get('/atletas', [AtletaController::class, 'index'])->name('atletas.index'); 
Route::post('/atleta/visualizar/{id}', [AtletaController::class, 'registrarVisualizacao'])->name('atleta.visualizar');


