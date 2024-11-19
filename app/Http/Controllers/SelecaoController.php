<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Categoria;
use App\Models\Selecao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $max_upload_size = config('inscricoes.upload_max_filesize');
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
    public function update(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);

        // nome
        if ($selecao->nome != $request->nome && !empty($request->nome)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Nome antigo: ' . $selecao->nome . ' - Novo nome: ' . $request->nome);
            $selecao->nome = $request->nome;
        }
        
        // estado
        if ($selecao->estado != $request->estado && !empty($request->estado)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Status antigo: ' . $selecao->estado . ' - Novo status: ' . $request->estado);
            $selecao->estado = $request->estado;
        }

        // descrição
        if ($selecao->descricao != $request->descricao && !empty($request->descricao)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Descrição antiga: ' . $selecao->descricao . ' - Nova descrição: ' . $request->descricao);
            $selecao->descricao = $request->descricao;
        }

        // categoria_id
        if ($selecao->categoria_id != $request->categoria_id && !empty($request->categoria_id)) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Categoria antiga: ' . $selecao->categoria_id . ' - Nova categoria: ' . $request->categoria_id);
            $selecao->categoria_id = $request->categoria_id;
        }
        
        $selecao->save();
        
        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        
        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    private function monta_compact($modelo, $modo) {
        $data = (object) self::$data;
        $tipo_modelo = 'Selecao';
        $max_upload_size = config('inscricoes.upload_max_filesize');
    
        return compact('data', 'modelo', 'tipo_modelo', 'modo', 'max_upload_size');
    }
}
