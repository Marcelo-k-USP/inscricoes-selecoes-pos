<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinhaPesquisaRequest;
use App\Models\LinhaPesquisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LinhaPesquisaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('linhaspesquisa.viewAny');
        \UspTheme::activeUrl('linhaspesquisa');

        $linhaspesquisa = LinhaPesquisa::with('programa')->get();
        $fields = LinhaPesquisa::getFields();

        # para o form de adicionar pessoas
        $modal_pessoa['url'] = 'linhas de pesquisa';
        $modal_pessoa['title'] = 'Adicionar Pessoa';

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $linhaspesquisa]);
        } else {
            $modal['url'] = 'linhaspesquisa';
            $modal['title'] = 'Editar Linha de Pesquisa';
            $rules = LinhaPesquisaRequest::rules;
            return view('linhaspesquisa.tree', compact('linhaspesquisa', 'fields', 'modal', 'modal_pessoa', 'rules'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  string                     $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $id)
    {
        $this->authorize('linhaspesquisa.view');
        \UspTheme::activeUrl('linhaspesquisa');

        if ($request->ajax()) {
            $linhapesquisa = LinhaPesquisa::find((int) $id);    // preenche os dados do form de edição de uma linha de pesquisa
            $linhapesquisa->codpes_orientador = $linhapesquisa->codpes_orientador . ' ' . (new UserController)->codpes(new Request(['term' => $linhapesquisa->codpes_orientador]));
            return $linhapesquisa;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LinhaPesquisaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinhaPesquisaRequest $request)
    {
        $this->authorize('linhaspesquisa.create');

        $validator = Validator::make($request->all(), LinhaPesquisaRequest::rules, LinhaPesquisaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $linhapesquisa = LinhaPesquisa::create($request->all());

        $request->session()->flash('alert-success', 'Dados adicionados com sucesso');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LinhaPesquisaRequest  $request
     * @param  string                                   $id
     * @return \Illuminate\Http\Response
     */
    public function update(LinhaPesquisaRequest $request, string $id)
    {
        $this->authorize('linhaspesquisa.update');

        $validator = Validator::make($request->all(), LinhaPesquisaRequest::rules, LinhaPesquisaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $linhapesquisa = LinhaPesquisa::find((int) $id);
        $linhapesquisa->fill($request->all());
        $linhapesquisa->save();

        $request->session()->flash('alert-success', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\LinhaPesquisaRequest  $request
     * @param  string                                   $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LinhaPesquisaRequest $request, string $id)
    {
        $this->authorize('linhaspesquisa.delete');

        $linhapesquisa = LinhaPesquisa::find((int) $id);
        if ($linhapesquisa->selecoes()->exists()) {
            $request->session()->flash('alert-danger', 'Há seleções para esta linha de pesquisa!');
            return back();
        }
        $linhapesquisa->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }
}
