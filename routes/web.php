<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtletaController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\AdminAtletaController;
use App\Http\Controllers\AdminOlheiroController;
use App\Http\Controllers\OlheiroAuthController;
use App\Http\Controllers\OlheiroAreaController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// rota de login
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');

// submissao do formulario de login
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

Route::post('/logout', [App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');

Route::get('/olheiro/login', [OlheiroAuthController::class, 'showLoginForm'])->name('olheiro.login.form');
Route::get('/olheiro/cadastro', [OlheiroAuthController::class, 'showRegisterForm'])->name('olheiro.register.form');
Route::post('/olheiro/cadastro', [OlheiroAuthController::class, 'register'])->name('olheiro.register');
Route::post('/olheiro/login', [OlheiroAuthController::class, 'login'])->name('olheiro.login');
Route::post('/olheiro/logout', [OlheiroAuthController::class, 'logout'])->name('olheiro.logout');

// area protegida de administracao
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    Route::get('/atletas/template', [AtletaController::class, 'downloadTemplate'])->name('atletas.template');
    Route::get('/atletas/importar', [AtletaController::class, 'showImportForm'])->name('atletas.import.form');
    Route::post('/atletas/importar', [AtletaController::class, 'import'])->name('atletas.import');
    Route::get('/atletas/create', [AtletaController::class, 'create'])->name('atletas.create');
    Route::post('/atletas', [AtletaController::class, 'store'])->name('atletas.store');
    Route::get('/atletas/{id}/edit', [AtletaController::class, 'edit'])->name('atletas.edit');
    Route::put('/atletas/{id}', [AtletaController::class, 'update'])->name('atletas.update');
    Route::delete('/atletas/{id}', [AtletaController::class, 'destroy'])->name('atletas.destroy');
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/atletas', [AdminAtletaController::class, 'index'])->name('admin.index');
    Route::get('/olheiros', [AdminOlheiroController::class, 'index'])->name('admin.olheiros.index');
    Route::patch('/olheiros/{id}/aprovar', [AdminOlheiroController::class, 'approve'])->name('admin.olheiros.approve');
    Route::get('/olheiros/{id}/edit', [AdminOlheiroController::class, 'edit'])->name('admin.olheiros.edit');
    Route::put('/olheiros/{id}', [AdminOlheiroController::class, 'update'])->name('admin.olheiros.update');
    Route::delete('/olheiros/{id}', [AdminOlheiroController::class, 'destroy'])->name('admin.olheiros.destroy');
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/busca-atletas', [AtletaController::class, 'buscaAtletas'])->name('admin.buscaAtletas');
});

Route::get('/atletas/buscar', [AtletaController::class, 'buscar'])->name('atletas.buscar');
Route::get('/atletas/{id}/og-image', [AtletaController::class, 'ogImage'])->name('atletas.og-image');
Route::get('/atletas/{id}', [AtletaController::class, 'show'])->name('atletas.show');
Route::get('/perfil-atleta/{id}', [AtletaController::class, 'show'])->name('atletas.perfil');
Route::get('/atletas', [AtletaController::class, 'index'])->name('atletas.index');
Route::post('/atleta/visualizar/{id}', [AtletaController::class, 'registrarVisualizacao'])->name('atleta.visualizar');

Route::middleware('auth:olheiro')->prefix('olheiro')->group(function () {
    Route::get('/atletas', [OlheiroAreaController::class, 'index'])->name('olheiro.atletas.index');
    Route::post('/favoritos/{atleta}', [OlheiroAreaController::class, 'toggleFavorito'])->name('olheiro.favoritos.toggle');

    Route::post('/shortlists', [OlheiroAreaController::class, 'storeShortlist'])->name('olheiro.shortlists.store');
    Route::delete('/shortlists/{shortlist}', [OlheiroAreaController::class, 'destroyShortlist'])->name('olheiro.shortlists.destroy');

    Route::post('/shortlists/{shortlist}/atletas/{atleta}', [OlheiroAreaController::class, 'addAtletaNaShortlist'])
        ->name('olheiro.shortlists.atletas.store');
    Route::patch('/shortlists/{shortlist}/itens', [OlheiroAreaController::class, 'updateShortlistItens'])
        ->name('olheiro.shortlists.itens.update');
    Route::patch('/shortlists/{shortlist}/atletas/{atleta}', [OlheiroAreaController::class, 'updateShortlistItem'])
        ->name('olheiro.shortlists.atletas.update');
    Route::delete('/shortlists/{shortlist}/atletas/{atleta}', [OlheiroAreaController::class, 'removeShortlistItem'])
        ->name('olheiro.shortlists.atletas.destroy');
});
