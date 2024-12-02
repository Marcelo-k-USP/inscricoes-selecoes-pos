<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinhaPesquisaRequest;
use App\Models\LinhaPesquisa;
use App\Models\Selecao;
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
     * Mostra lista de linhas de pesquisa
     */
    public function index(Request $request)
    {
        $this->authorize('linhaspesquisa.viewAny');
        \UspTheme::activeUrl('linhaspesquisa');

        $linhaspesquisa = LinhaPesquisa::all();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        #usando no ajax, somente para admin
        $this->authorize('admin');
        \UspTheme::activeUrl('linhaspesquisa');

        if ($request->ajax()) {
            # preenche os dados do form de edição de uma linha de pesquisa
            $linhapesquisa = LinhaPesquisa::find($id);
            $linhapesquisa->codpes_docente = $linhapesquisa->codpes_docente . ' ' . (new UserController)->codpes(new Request(['term' => $linhapesquisa->codpes_docente]));
            return $linhapesquisa;
        } else {
            # desativado por enquanto
            return false;
            return view('linhaspesquisa.show', compact('linhapesquisa'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinhaPesquisaRequest $request)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LinhaPesquisaRequest::rules, LinhaPesquisaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $linhapesquisa = LinhaPesquisa::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($linhapesquisa->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LinhaPesquisaRequest $request, $id)
    {
        $this->authorize('admin');

        $validator = Validator::make($request->all(), LinhaPesquisaRequest::rules, LinhaPesquisaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $linhapesquisa = LinhaPesquisa::find($id);
        $linhapesquisa->fill($request->all());
        $linhapesquisa->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LinhaPesquisaRequest $request, $id)
    {
        $this->authorize('admin');

        $linhapesquisa = LinhaPesquisa::find($id);
        if ($linhapesquisa->selecoes()->exists()) {
            $request->session()->flash('alert-danger', 'Há seleções para esta linha de pesquisa!');
            return back();
        }
        $linhapesquisa->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }
}
