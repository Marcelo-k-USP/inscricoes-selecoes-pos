<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\LinhaPesquisaController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\SelecaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [IndexController::class, 'index'])->name('home');

/* Senha única */
Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// INSCRIÇÕES
Route::get('inscricoes', [InscricaoController::class, 'index'])->name('inscricoes.index');
Route::get('inscricoes/create', [InscricaoController::class, 'listaSelecoes'])->name('inscricoes.create');
Route::get('inscricoes/create/{selecao}', [InscricaoController::class, 'create'])->name('inscricoes.create');
Route::post('inscricoes/create', [InscricaoController::class, 'store'])->name('inscricoes.store');
Route::get('inscricoes/edit/{inscricao}', [InscricaoController::class, 'edit'])->name('inscricoes.edit');
Route::put('inscricoes/edit/{inscricao}', [InscricaoController::class, 'update'])->name('inscricoes.update');

// CATEGORIAS
Route::resource('categorias', CategoriaController::class);

// PROGRAMAS
Route::resource('programas', ProgramaController::class);

// LINHAS DE PESQUISA
Route::resource('linhaspesquisa', LinhaPesquisaController::class);

// SELEÇÕES
Route::get('selecoes', [SelecaoController::class, 'index'])->name('selecoes.index');
Route::get('selecoes/create', [SelecaoController::class, 'create'])->name('selecoes.create');
Route::post('selecoes/create', [SelecaoController::class, 'store'])->name('selecoes.store');
Route::get('selecoes/edit/{selecao}', [SelecaoController::class, 'edit'])->name('selecoes.edit');
Route::put('selecoes/edit/{selecao}', [SelecaoController::class, 'update'])->name('selecoes.update');
Route::put('selecoes/edit-status/{selecao}', [SelecaoController::class, 'updateStatus'])->name('selecoes.update-status');

// SELEÇÕES - LINHAS DE PESQUISA
Route::post('selecoes/{selecao}/linhaspesquisa', [SelecaoController::class, 'storeLinhaPesquisa']);
Route::delete('selecoes/{selecao}/linhaspesquisa/{linhapesquisa}', [SelecaoController::class, 'destroyLinhaPesquisa']);

// SELEÇÕES - FORMULÁRIO
Route::post('selecoes/{selecao}/template_json', [SelecaoController::class, 'storeTemplateJson']);
Route::get('selecoes/{selecao}/template', [SelecaoController::class, 'createTemplate'])->name('selecoes.createtemplate');
Route::post('selecoes/{selecao}/template', [SelecaoController::class, 'storeTemplate'])->name('selecoes.storetemplate');
Route::get('selecoes/{selecao}/templatevalue', [SelecaoController::class, 'createTemplateValue'])->name('selecoes.createtemplatevalue');
Route::post('selecoes/{selecao}/templatevalue', [SelecaoController::class, 'storeTemplateValue'])->name('selecoes.storetemplatevalue');

// USERS
Route::get('search/partenome', [UserController::class, 'partenome']);
Route::get('search/codpes', [UserController::class, 'codpes']);
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
