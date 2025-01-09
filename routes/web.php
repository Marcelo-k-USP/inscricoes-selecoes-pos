<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\LinhaPesquisaController;
use App\Http\Controllers\LocalUserController;
use App\Http\Controllers\MotivoIsencaoTaxaController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\SelecaoController;
use App\Http\Controllers\SolicitacaoIsencaoTaxaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [IndexController::class, 'index'])->name('home');

// SENHA ÚNICA
Route::get('login', [LoginController::class, 'redirectToProvider'])->name('login');
Route::get('callback', [LoginController::class, 'handleProviderCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// SOLICITAÇÕES DE ISENÇÃO DE TAXA
Route::get('solicitacoesisencaotaxa', [SolicitacaoIsencaoTaxaController::class, 'index'])->name('solicitacoesisencaotaxa.index');
Route::get('solicitacoesisencaotaxa/create', [SolicitacaoIsencaoTaxaController::class, 'listaSelecoesParaSolicitacaoIsencaoTaxa'])->name('solicitacoesisencaotaxa.create');
Route::get('solicitacoesisencaotaxa/create/{selecao}', [SolicitacaoIsencaoTaxaController::class, 'create'])->name('solicitacoesisencaotaxa.create.selecao');
Route::post('solicitacoesisencaotaxa/create', [SolicitacaoIsencaoTaxaController::class, 'store'])->name('solicitacoesisencaotaxa.store');

// INSCRIÇÕES
Route::get('inscricoes', [InscricaoController::class, 'index'])->name('inscricoes.index');
Route::get('inscricoes/create', [InscricaoController::class, 'listaSelecoesParaNovaInscricao'])->name('inscricoes.create');
Route::get('inscricoes/create/{selecao}', [InscricaoController::class, 'create'])->name('inscricoes.create.selecao');
Route::post('inscricoes/create', [InscricaoController::class, 'store'])->name('inscricoes.store');
Route::get('inscricoes/edit/{inscricao}', [InscricaoController::class, 'edit'])->name('inscricoes.edit');
Route::put('inscricoes/edit/{inscricao}', [InscricaoController::class, 'update'])->name('inscricoes.update');

// CONSULTA DE CEP
Route::get('consulta-cep', [EnderecoController::class, 'consultarCep'])->name('consulta.cep');

// CATEGORIAS
Route::resource('categorias', CategoriaController::class);

// PROGRAMAS
Route::resource('programas', ProgramaController::class);

// LINHAS DE PESQUISA
Route::resource('linhaspesquisa', LinhaPesquisaController::class);

// MOTIVOS DE ISENÇÃO DE TAXA
Route::resource('motivosisencaotaxa', MotivoIsencaoTaxaController::class);

// SELEÇÕES
Route::get('selecoes', [SelecaoController::class, 'index'])->name('selecoes.index');
Route::get('selecoes/create', [SelecaoController::class, 'create'])->name('selecoes.create');
Route::post('selecoes/create', [SelecaoController::class, 'store'])->name('selecoes.store');
Route::get('selecoes/edit/{selecao}', [SelecaoController::class, 'edit'])->name('selecoes.edit');
Route::put('selecoes/edit/{selecao}', [SelecaoController::class, 'update'])->name('selecoes.update');
Route::put('selecoes/edit-status/{selecao}', [SelecaoController::class, 'updateStatus'])->name('selecoes.update-status');
Route::get('selecoes/{selecao}/download', [SelecaoController::class, 'download'])->name('selecoes.download');

// SELEÇÕES - LINHAS DE PESQUISA
Route::post('selecoes/{selecao}/linhaspesquisa', [SelecaoController::class, 'storeLinhaPesquisa']);
Route::delete('selecoes/{selecao}/linhaspesquisa/{linhapesquisa}', [SelecaoController::class, 'destroyLinhaPesquisa']);

// SELEÇÕES - MOTIVOS DE ISENÇÂO DE TAXA
Route::post('selecoes/{selecao}/motivosisencaotaxa', [SelecaoController::class, 'storeMotivoIsencaoTaxa']);
Route::delete('selecoes/{selecao}/motivosisencaotaxa/{motivoisencaotaxa}', [SelecaoController::class, 'destroyMotivoIsencaoTaxa']);

// SELEÇÕES - FORMULÁRIO
Route::post('selecoes/{selecao}/template_json', [SelecaoController::class, 'storeTemplateJson']);
Route::get('selecoes/{selecao}/template', [SelecaoController::class, 'createTemplate'])->name('selecoes.createtemplate');
Route::post('selecoes/{selecao}/template', [SelecaoController::class, 'storeTemplate'])->name('selecoes.storetemplate');
Route::get('selecoes/{selecao}/templatevalue/{campo}', [SelecaoController::class, 'createTemplateValue'])->name('selecoes.createtemplatevalue')->where('campo', '[a-zA-Z0-9_]+');
Route::post('selecoes/{selecao}/templatevalue/{campo}', [SelecaoController::class, 'storeTemplateValue'])->name('selecoes.storetemplatevalue')->where('campo', '[a-zA-Z0-9_]+');

// USERS
Route::get('search/partenome', [UserController::class, 'partenome']);
Route::get('search/codpes', [UserController::class, 'codpes']);
Route::get('users/perfil/{perfil}', [UserController::class, 'trocarPerfil']);
// Route::get('users/desassumir', [UserController::class, 'desassumir']);
// Route::get('users/{user}/assumir', [UserController::class, 'assumir']);
Route::get('users/meuperfil', [UserController::class, 'meuperfil']);
Route::resource('users', UserController::class);

// LOCAL USERS - LOGIN
Route::get('localusers/login', [LocalUserController::class, 'showLogin'])->name('localusers.showlogin');
Route::post('localusers/login', [LocalUserController::class, 'login'])->name('localusers.login');
Route::post('localusers/esqueceusenha', [LocalUserController::class, 'esqueceuSenha'])->name('localusers.esqueceusenha');
Route::get('localusers/redefinesenha/{token}', [LocalUserController::class, 'iniciaRedefinicaoSenha'])->name('localusers.iniciaredefinicaosenha');
Route::post('localusers/redefinesenha', [LocalUserController::class, 'redefineSenha'])->name('localusers.redefinesenha');
Route::get('localusers/confirmaemail/{token}', [LocalUserController::class, 'confirmaEmail'])->name('localusers.confirmaemail');

// LOCAL USERS
Route::resource('localusers', LocalUserController::class);

// PARÂMETROS
Route::get('parametros', [ParametroController::class, 'edit'])->name('parametros.edit');
Route::put('parametros', [ParametroController::class, 'update'])->name('parametros.update');

// ARQUIVOS
Route::resource('arquivos', ArquivoController::class);

// ADMIN
Route::get('admin', [AdminController::class, 'index']);
Route::get('admin/get_oauth_file/{filename}', [AdminController::class, 'getOauthFile']);
