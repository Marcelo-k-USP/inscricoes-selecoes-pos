<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinhaPesquisaRequest;
use App\Models\LinhaPesquisa;
use App\Models\Orientador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LinhaPesquisaController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Linhas de Pesquisa',
        'url' => 'linhaspesquisa',     // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\LinhaPesquisa',
    ];

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

        $linhaspesquisa = LinhaPesquisa::with('programa')->orderBy('programa_id')->orderBy('id')->get();
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('linhaspesquisa.create');

        \UspTheme::activeUrl('linhaspesquisa');
        return view('linhaspesquisa.edit', $this->monta_compact(new LinhaPesquisa, 'create'));
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
        if ($validator->fails()) {
            \UspTheme::activeUrl('linhaspesquisa');
            return back()->withErrors($validator)->withInput();
        }

        $linhapesquisa = LinhaPesquisa::create($request->all());

        $request->session()->flash('alert-success', 'Linha de pesquisa cadastrada com sucesso');

        \UspTheme::activeUrl('linhaspesquisa');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\LinhaPesquisa  $linhapesquisa
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('linhaspesquisa.update');

        \UspTheme::activeUrl('linhaspesquisa');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LinhaPesquisaRequest  $request
     * @param  \App\Models\LinhaPesquisa                $linhapesquisa
     * @return \Illuminate\Http\Response
     */
    public function update(LinhaPesquisaRequest $request, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('linhaspesquisa.update');

        $validator = Validator::make($request->all(), LinhaPesquisaRequest::rules, LinhaPesquisaRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('linhaspesquisa');
            return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'))->withErrors($validator);    // preciso especificar 'edit'... se eu fizesse um return back(), e o usuário estivesse vindo de um update após um create, a variável $modo voltaria a ser 'create', e a página ficaria errada
        }

        $linhapesquisa->nome = $request->nome;
        $linhapesquisa->programa_id = $request->programa_id;
        $linhapesquisa->save();

        $request->session()->flash('alert-success', 'Linha de pesquisa alterada com sucesso');

        \UspTheme::activeUrl('linhaspesquisa');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
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

    /**
     * Adicionar orientadores relacionados à linha de pesquisa
     * autorizado a qualquer um que tenha acesso à linha de pesquisa
     * request->codpes = required, int
     */
    public function storeOrientador(Request $request, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('linhaspesquisa.update');

        $request->validate([
            'codpes' => 'required',
        ],
        [
            'codpes.required' => 'Orientador obrigatório',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $linhapesquisa) {

            $orientador = Orientador::where('codpes', $request->codpes)->first();
            if (is_null($orientador))
                $orientador = Orientador::create($request->all());

            $existia = $linhapesquisa->orientadores()->detach($orientador);

            $linhapesquisa->orientadores()->attach($orientador);

            return ['orientador' => $orientador, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'O orientador ' . $db_transaction['orientador']->codpes . ' foi adicionado à essa linha de pesquisa.');
        else
            $request->session()->flash('alert-info', 'O orientador ' . $db_transaction['orientador']->codpes . ' já estava vinculado à essa linha de pesquisa.');
        return back();
    }

    /**
     * Remove orientadores relacionados à linha de pesquisa
     * $user = required
     */
    public function destroyOrientador(Request $request, LinhaPesquisa $linhapesquisa, Orientador $orientador)
    {
        $this->authorize('linhaspesquisa.update');

        $linhapesquisa->orientadores()->detach($orientador);

        $request->session()->flash('alert-success', 'O orientador ' . $orientador->codpes . ' foi removido dessa linha de pesquisa.');
        return back();
    }

    private function monta_compact(LinhaPesquisa $linhapesquisa, string $modo)
    {
        $data = (object) self::$data;
        $objeto = $linhapesquisa;
        $fields_orientador = Orientador::getFields();

        return compact('data', 'objeto', 'fields_orientador', 'modo');
    }
}
