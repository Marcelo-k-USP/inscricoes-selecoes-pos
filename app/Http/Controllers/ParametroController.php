<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParametroRequest;
use App\Models\Parametro;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ParametroController extends Controller
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
    public function edit($id = null)
    {
        Gate::authorize('parametros.update');

        \UspTheme::activeUrl('parametros');
        // MODO A: Se for parâmetro único, redireciona direto para o edit
        if (config('inscricoes-selecoes-pos.usar_parametro_unico')) {
            return view('parametros.edit', $this->monta_compact($id));
        }

        // MODO B: Busca os programas com seus respectivos parâmetros para montar um novo index
        $programas = Programa::with('parametro')->get();
        return view('parametros.index', compact('programas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ParametroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ParametroRequest $request)
    {
        Gate::authorize('parametros.update');

        $validator = Validator::make($request->all(), ParametroRequest::rules, ParametroRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        // Se vier 'programa_id' no request, e não for modo único, criamos um NOVO registro.
        // Caso contrário, buscamos o primeiro (global) como sempre foi.
        if (!config('app.usar_parametro_unico') && $request->filled('programa_id')) {
            $parametro = new Parametro;
        } else {
            $parametro = Parametro::first() ?: new Parametro;
        }

        $parametro->boleto_codigo_fonte_recurso = $request->boleto_codigo_fonte_recurso;
        $parametro->boleto_estrutura_hierarquica = $request->boleto_estrutura_hierarquica;
        $parametro->link_acompanhamento_especiais = $request->link_acompanhamento_especiais;
        $parametro->email_servicoposgraduacao = $request->email_servicoposgraduacao;
        $parametro->email_secaoinformatica = $request->email_secaoinformatica;
        $parametro->email_gerenciamentosite = $request->email_gerenciamentosite;
        $parametro->save();

        // Se criamos um parametro específico para cada programa, atualizamos o programa correspondente
        if (!config('app.usar_parametro_unico') && $request->filled('programa_id')) {
            $programa = Programa::find($request->programa_id);
            if ($programa) {
                $programa->parametro_id = $parametro->id;
                $programa->save();
            }
        }

        $request->session()->flash('alert-success', 'Dados editados com sucesso');
        \UspTheme::activeUrl('parametros');
        return view('parametros.edit', $this->monta_compact());
    }

    private function monta_compact($id = null)
    {
        // Se passar ID, busca o específico. Se não, busca o primeiro (global).
        // Se a tabela estiver vazia, cria uma instância vazia para a View não dar erro.
        $parametros = $id ? Parametro::find($id) : (Parametro::first() ?: new Parametro);
        
        $fields = Parametro::getFields();
        $rules = ParametroRequest::rules;
        
        // Injeta a lista apenas se o sistema permitir múltiplos parâmetros
        $programasSemParametro = !config('app.usar_parametro_unico') 
            ? Programa::whereNull('parametro_id')->get() : collect();

        return compact('parametros', 'fields', 'rules', 'programasSemParametro');
    }
}
