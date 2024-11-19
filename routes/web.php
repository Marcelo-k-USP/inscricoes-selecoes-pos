<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\SelecaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [IndexController::class, 'index'])->name('home');

/* Senha única */
Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// INSCRIÇÕES
Route::get('inscricoes/create', [InscricaoController::class, 'listaSelecoes']);
Route::get('inscricoes/create/{selecao}', [InscricaoController::class, 'create'])->name('inscricoes.create');
Route::post('inscricoes/create/{selecao}', [InscricaoController::class, 'store'])->name('inscricoes.store');
Route::resource('inscricoes', InscricaoController::class)->parameters(['inscricoes' => 'inscricao'])->except(['create', 'store']);

// PROCESSOS
Route::resource('processos', ProcessoController::class);

// SELEÇÕES
Route::get('selecoes', [SelecaoController::class, 'index'])->name('selecoes.index');
Route::get('selecoes/create', [SelecaoController::class, 'create'])->name('selecoes.create');
Route::post('selecoes/create', [SelecaoController::class, 'store'])->name('selecoes.store');
Route::get('selecoes/edit/{selecao}', [SelecaoController::class, 'edit'])->name('selecoes.edit');
Route::put('selecoes/edit/{selecao}', [SelecaoController::class, 'update'])->name('selecoes.update');

// USERS
Route::get('search/partenome', [UserController::class, 'partenome']);
Route::get('users/perfil/{perfil}', [UserController::class, 'trocarPerfil']);
// Route::get('users/desassumir', [UserController::class, 'desassumir']);
// Route::get('users/{user}/assumir', [UserController::class, 'assumir']);
Route::get('users/meuperfil', [UserController::class, 'meuperfil']);
Route::resource('users', UserController::class);

// ARQUIVOS
Route::resource('arquivos', ArquivoController::class);

// ADMIN
Route::get('admin', [AdminController::class, 'index']);
Route::get('admin/get_oauth_file/{filename}', [AdminController::class, 'getOauthFile']);
