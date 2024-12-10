<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParametroRequest;
use App\Models\Parametro;
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
    public function edit()
    {
        $this->authorize('parametros.update');
        \UspTheme::activeUrl('parametros');

        $parametros = Parametro::first();    // preenche os dados do form de edição dos parâmetros
        $fields = Parametro::getFields();
        $rules = ParametroRequest::rules;
        return view('parametros.edit', compact('parametros', 'fields', 'rules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ParametroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ParametroRequest $request)
    {
        $this->authorize('parametros.update');

        $request->merge(['boleto_valor' => floatval(str_replace(',', '.', $request->boleto_valor))]);
        $validator = Validator::make($request->all(), ParametroRequest::rules, ParametroRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $parametro = Parametro::first();
        $parametro->boleto_valor = $request->boleto_valor;
        $parametro->save();

        $request->session()->flash('alert-success', 'Dados editados com sucesso');
        return back();
    }
}
