<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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
Route::get('inscricoes/create/{selecao}/', [InscricaoController::class, 'create'])->name('inscricoes.create');
Route::post('inscricoes/create/{selecao}/', [InscricaoController::class, 'store'])->name('inscricoes.store');
Route::resource('inscricoes', InscricaoController::class)->parameters(['inscricoes' => 'inscricao'])->except(['create', 'store']);

// PROCESSOS
Route::resource('processos', ProcessoController::class);

// SELEÇÕES
Route::resource('selecoes', SelecaoController::class)->parameters(['selecoes' => 'selecao'])->except(['create', 'destroy', 'edit']);

// USERS
Route::get('search/partenome', [UserController::class, 'partenome']);
Route::get('users/perfil/{perfil}', [UserController::class, 'trocarPerfil']);
// Route::get('users/desassumir', [UserController::class, 'desassumir']);
// Route::get('users/{user}/assumir', [UserController::class, 'assumir']);
Route::get('users/meuperfil', [UserController::class, 'meuperfil']);
Route::resource('users', UserController::class);

// ADMIN
Route::get('admin', [AdminController::class, 'index']);
Route::get('admin/get_oauth_file/{filename}', [AdminController::class, 'getOauthFile']);
