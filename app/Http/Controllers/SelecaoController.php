<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Categoria;
use App\Models\LinhaPesquisa;
use App\Models\Selecao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SelecaoController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Seleções',
        'url' => 'selecoes',     // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\Selecao',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista as seleções
     */
    public function index()
    {
        $this->authorize('selecoes.viewAny');

        \UspTheme::activeUrl('selecoes');
        $data = self::$data;
        $modelos = Selecao::listarSelecoes();
        $tipo_modelo = 'Selecao';
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        return view('selecoes.index', compact('data', 'modelos', 'tipo_modelo', 'max_upload_size'));
    }

    public function create()
    {
        $this->authorize('selecoes.create');
        
        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact(new Selecao, 'create'));
    }
    
    /**
     * Criar nova seleção
     */
    public function store(SelecaoRequest $request)
    {
        $categoria = Categoria::find($request->categoria_id);
        $this->authorize('selecoes.create', $categoria);

        $selecao = Selecao::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);
        
        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SelecaoRequest $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);

        // categoria_id
        if ($selecao->categoria_id != $request->categoria_id && !empty($request->categoria_id)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Categoria antiga: ' . $selecao->categoria_id . ' - Nova categoria: ' . $request->categoria_id);
            $selecao->categoria_id = $request->categoria_id;
        }
        
        // nome
        if ($selecao->nome != $request->nome && !empty($request->nome)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Nome antigo: ' . $selecao->nome . ' - Novo nome: ' . $request->nome);
            $selecao->nome = $request->nome;
        }
        
        // descrição
        if ($selecao->descricao != $request->descricao && !empty($request->descricao)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Descrição antiga: ' . $selecao->descricao . ' - Nova descrição: ' . $request->descricao);
            $selecao->descricao = $request->descricao;
        }

        // programa_id
        if ($selecao->programa_id != $request->programa_id && !empty($request->programa_id)) {
            if ($selecao->linhaspesquisa->count() > 0) {
                $request->session()->flash('alert-danger', 'Não se pode alterar o programa, pois há linhas de pesquisa do programa antigo cadastradas para esta seleção!');
                return back();
            }
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Programa antigo: ' . $selecao->programa_id . ' - Novo programa: ' . $request->programa_id);
            $selecao->programa_id = $request->programa_id;
        }
        
        $selecao->save();
        
        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        
        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    public function updateStatus(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);

        // estado
        if ($selecao->estado != $request->estado && !empty($request->estado)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Status antigo: ' . $selecao->estado . ' - Novo status: ' . $request->estado);
            $selecao->estado = $request->estado;
        }

        $selecao->save();
        
        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        
        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    /**
     * Adicionar linhas de pesquisa relacionadas à seleção
     * autorizado a qualquer um que tenha acesso à seleção
     * request->codpes = required, int
     */
    public function storeLinhaPesquisa(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Linha de pesquisa obrigatória',
        ]);

        $linhapesquisa = LinhaPesquisa::where('id', $request->id)->first();

        $existia = $selecao->linhaspesquisa()->detach($linhapesquisa);

        $selecao->linhaspesquisa()->attach($linhapesquisa);

        if (!$existia)
            $request->session()->flash('alert-info', 'A linha de pesquisa ' . $linhapesquisa->nome . ' foi adicionada à essa seleção.');
        else
            $request->session()->flash('alert-info', 'A linha de pesquisa ' . $linhapesquisa->nome . ' já estava vinculada à essa seleção.');

        return Redirect::to(URL::previous() . '#card_linhaspesquisa');
    }

    /**
     * Remove linhas de pesquisa relacionadas à seleção
     * $user = required
     */
    public function destroyLinhaPesquisa(Request $request, Selecao $selecao, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('selecoes.update', $selecao);

        $selecao->linhaspesquisa()->detach($linhapesquisa);

        $request->session()->flash('alert-info', 'A linha de pesquisa ' . $linhapesquisa->nome . ' foi removida dessa seleção.');

        return Redirect::to(URL::previous() . '#card_linhaspesquisa');
    }

    private function monta_compact($modelo, $modo) {
        $data = (object) self::$data;
        $tipo_modelo = 'Selecao';
        $linhaspesquisa = LinhaPesquisa::listarLinhasPesquisa($modelo->programa);
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
    
        return compact('data', 'modelo', 'tipo_modelo', 'modo', 'linhaspesquisa', 'max_upload_size');
    }
}
