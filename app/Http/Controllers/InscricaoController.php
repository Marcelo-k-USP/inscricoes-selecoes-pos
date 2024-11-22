<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Models\Inscricao;
use App\Models\Selecao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class InscricaoController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Inscrições',
        'url' => 'inscricoes',     // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\Inscricao',
    ];
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('inscricoes.viewAny');

        \UspTheme::activeUrl('inscricoes');
        $data = self::$data;
        $modelos = Inscricao::listarInscricoes();
        $tipo_modelo = 'Inscricao';
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        return view('inscricoes.index', compact('data', 'modelos', 'tipo_modelo', 'max_upload_size'));
    }

    /**
     * Mostra lista de seleções e respectivas categorias
     * para selecionar e criar nova inscrição
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function listaSelecoes(Request $request)
    {
        \UspTheme::activeUrl('inscricoes/create');

        $request->validate([
            'filtro' => 'nullable|string',
        ]);

        $categorias = Selecao::listarSelecoesParaNovaInscricao();
        return view('inscricoes.listaselecoes', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Selecao $selecao)
    {
        $this->authorize('inscricoes.create');

        \UspTheme::activeUrl('inscricoes');
        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InscricaoRequest $request)
    {
        $selecao = Selecao::find($request->selecao_id);
        $this->authorize('inscricoes.create', $selecao);

        $inscricao = Inscricao::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Inscricao $inscricao)
    {
        $this->authorize('inscricoes.view', $inscricao);
        
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscricao $inscricao)
    {
        $this->authorize('inscricoes.view', $inscricao);

        $inscricao->save();
        
        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    private function monta_compact($modelo, $modo) {
        $data = (object) self::$data;
        $tipo_modelo = 'Inscricao';
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
    
        return compact('data', 'modelo', 'tipo_modelo', 'modo', 'max_upload_size');
    }
}
