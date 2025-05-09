<?php

use App\Http\Controllers\AtletaController;
use Illuminate\Support\Facades\Route;

Route::get('/atletas/buscar', [AtletaController::class, 'buscar']);
Route::get('/atletas/buscar-cpf', [AtletaController::class, 'buscarPorCpf']); // Buscar por CPF
Route::get('/atletas', [AtletaController::class, 'index']); // Listar todos
Route::get('/atletas/{id}', [AtletaController::class, 'show']); // Buscar por ID
Route::post('/atletas', [AtletaController::class, 'store']); // Criar novo
Route::put('/atletas/{id}', [AtletaController::class, 'update']); // Atualizar
Route::delete('/atletas/{id}', [AtletaController::class, 'destroy']); // Excluir

Route::get('/', function () {
    return view('welcome');
});
