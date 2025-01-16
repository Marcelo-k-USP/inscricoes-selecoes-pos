<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class FuncaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->authorize('funcoes.update');
        \UspTheme::activeUrl('funcoes');

        $programas_secretarios = Programa::with(['users' => function ($query) {
            $query->where('funcao', 'Secretários(as) dos Programas')
                  ->orderBy('user_programa.programa_id')
                  ->orderBy('user_programa.user_id');
        }])->get();
        $programas_coordenadores = Programa::with(['users' => function ($query) {
            $query->where('funcao', 'Coordenadores dos Programas')
                  ->orderBy('user_programa.programa_id')
                  ->orderBy('user_programa.user_id');
        }])->get();
        $posgraduacao_servico = User::whereHas('programas', function ($query) {
            $query->where('funcao', 'Serviço de Pós-Graduação')
                  ->orderBy('user_programa.programa_id')
                  ->orderBy('user_programa.user_id');
        })->with(['programas' => function ($query) {
            $query->where('funcao', 'Serviço de Pós-Graduação');
        }])->get();
        $posgraduacao_coordenadores = User::whereHas('programas', function ($query) {
            $query->where('funcao', 'Coordenadores da Pós-Graduação')
                  ->orderBy('user_programa.programa_id')
                  ->orderBy('user_programa.user_id');
        })->with(['programas' => function ($query) {
            $query->where('funcao', 'Coordenadores da Pós-Graduação');
        }])->get();

        return view('funcoes.edit', compact('programas_secretarios', 'programas_coordenadores', 'posgraduacao_servico', 'posgraduacao_coordenadores'));
    }
}
