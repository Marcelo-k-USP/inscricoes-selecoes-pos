<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinhaPesquisaRequest;
use App\Models\LinhaPesquisa;
use App\Models\Nivel;
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
        'title' => 'Linhas de Pesquisa/Temas',
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
        if (!$request->ajax())
            return view('linhaspesquisa.tree', $this->monta_compact_index());
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

        foreach (Nivel::all() as $nivel)    // cadastra automaticamente todos os níveis como possíveis para esta linha de pesquisa/tema
            $linhapesquisa->niveis()->attach($nivel);

        $request->session()->flash('alert-success', 'Linha de pesquisa/tema cadastrado com sucesso');
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

        $request->session()->flash('alert-success', 'Linha de pesquisa/tema alterado com sucesso');
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
        if ($linhapesquisa->selecoes()->exists())
            $request->session()->flash('alert-danger', 'Há seleções para esta linha de pesquisa/tema!');
        else {
            $linhapesquisa->delete();
            $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        }
        \UspTheme::activeUrl('linhaspesquisa');
        return view('linhaspesquisa.tree', $this->monta_compact_index());
    }

    /**
     * Adicionar níveis relacionados à linha de pesquisa/tema
     * autorizado a qualquer um que tenha acesso à linha de pesquisa/tema
     * request->codpes = required, int
     */
    public function storeNivel(Request $request, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('linhaspesquisa.update', $linhapesquisa);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Nível obrigatório',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $linhapesquisa) {

            $nivel = Nivel::where('id', $request->id)->first();

            $existia = $linhapesquisa->niveis()->detach($nivel);

            $linhapesquisa->niveis()->attach($nivel);

            return ['nivel' => $nivel, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'O nível ' . $db_transaction['nivel']->nome . ' foi adicionado à essa linha de pesquisa/tema');
        else
            $request->session()->flash('alert-info', 'O nível ' . $db_transaction['nivel']->nome . ' já estava vinculado à essa linha de pesquisa/tema');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    /**
     * Remove níveis relacionados à linha de pesquisa/tema
     * $user = required
     */
    public function destroyNivel(Request $request, LinhaPesquisa $linhapesquisa, Nivel $nivel)
    {
        $this->authorize('linhaspesquisa.update', $linhapesquisa);

        $linhapesquisa->niveis()->detach($nivel);

        $request->session()->flash('alert-success', 'O nível ' . $nivel->nome . ' foi removido dessa linha de pesquisa/tema');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    /**
     * Adicionar orientadores relacionados à linha de pesquisa/tema
     * autorizado a qualquer um que tenha acesso à linha de pesquisa/tema
     * request->codpes = required, int
     */
    public function storeOrientador(Request $request, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('linhaspesquisa.update');

        if ($request->externo)
            $request->validate([
                'externo_nome' => 'required',
                'externo_codpes' => 'required',
                'externo_email' => 'required',
            ], [
                'externo_nome.required' => 'Nome obrigatório',
                'externo_codpes.required' => 'Número USP obrigatório',
                'externo_email.required' => 'E-mail obrigatório',
                'externo_email.email' => 'O e-mail não é válido!',
            ]);
        else
            $request->validate([
                'codpes' => 'required',
            ], [
                'codpes.required' => 'Orientador obrigatório',
            ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $linhapesquisa) {

            if ($request->externo) {
                $orientador = Orientador::where('codpes', $request->externo_codpes)->first();
                if (is_null($orientador)) {
                    $orientador = new Orientador();
                    $orientador->codpes = $request->externo_codpes;
                    $orientador->nome = $request->externo_nome;
                    $orientador->email = $request->externo_email;
                    $orientador->externo = true;
                    $orientador->save();
                }
            } else {
                $orientador = Orientador::where('codpes', $request->codpes)->first();
                if (is_null($orientador))
                    $orientador = Orientador::create($request->all());
            }

            $existia = $linhapesquisa->orientadores()->detach($orientador);

            $linhapesquisa->orientadores()->attach($orientador);

            return ['orientador' => $orientador, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'O orientador ' . Orientador::obterNome($db_transaction['orientador']->codpes) . ' foi adicionado à essa linha de pesquisa/tema');
        else
            $request->session()->flash('alert-info', 'O orientador ' . Orientador::obterNome($db_transaction['orientador']->codpes) . ' já estava vinculado à essa linha de pesquisa/tema');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    /**
     * Remove orientadores relacionados à linha de pesquisa/tema
     * $user = required
     */
    public function destroyOrientador(Request $request, LinhaPesquisa $linhapesquisa, Orientador $orientador)
    {
        $this->authorize('linhaspesquisa.update');

        $linhapesquisa->orientadores()->detach($orientador);

        $request->session()->flash('alert-success', 'O orientador ' . Orientador::obterNome($orientador->codpes) . ' foi removido dessa linha de pesquisa/tema');
        return view('linhaspesquisa.edit', $this->monta_compact($linhapesquisa, 'edit'));
    }

    private function monta_compact_index()
    {
        $linhaspesquisa = LinhaPesquisa::with('programa')->orderBy('programa_id')->orderBy('id')->get();
        $fields = LinhaPesquisa::getFields();
        $modal_pessoa['url'] = 'linhas de pesquisa/temas';
        $modal_pessoa['title'] = 'Adicionar Pessoa';
        $modal['url'] = 'linhaspesquisa';
        $modal['title'] = 'Editar Linha de Pesquisa/Tema';
        $rules = LinhaPesquisaRequest::rules;

        return compact('linhaspesquisa', 'fields', 'modal', 'modal_pessoa', 'rules');
    }

    private function monta_compact(LinhaPesquisa $linhapesquisa, string $modo)
    {
        $data = (object) self::$data;
        if (!is_null($linhapesquisa) && !is_null($linhapesquisa->orientadores))
            foreach ($linhapesquisa->orientadores as $orientador)
                $orientador->nome = Orientador::obterNome($orientador->codpes);
        $objeto = $linhapesquisa;
        $fields_orientador = Orientador::getFields();
        $niveis = Nivel::all();

        return compact('data', 'objeto', 'fields_orientador', 'niveis', 'modo');
    }
}
