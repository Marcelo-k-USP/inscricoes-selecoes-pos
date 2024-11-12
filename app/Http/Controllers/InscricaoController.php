<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Models\Inscricao;
use App\Models\Selecao;
use App\Models\Processo;
use App\Models\User;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class InscricaoController extends Controller
{
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
        \UspTheme::activeUrl('inscricoes');

        $inscricoes = Inscricao::listarInscricoes();
        $modelo = 'Selecao';
        $max_upload_size = config('inscricoes.upload_max_filesize');
        return view('inscricoes/index', compact('inscricoes', 'modelo', 'max_upload_size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Selecao $selecao)
    {
        \UspTheme::activeUrl('inscricoes/create');

        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
        return view('inscricoes/create', compact('selecao', 'inscricao'));
    }

    /**
     * Mostra lista de seleções e respectivos processos
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

        $dtSearch = $request->filtro ?? '';

        $processos = Selecao::listarSelecoesParaNovaInscricao();
        return view('inscricoes.listaselecoes', compact('processos', 'dtSearch'));
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InscricaoRequest $request, Selecao $selecao)
    {
        $this->authorize('inscricoes.create', $selecao);

        # transaction para não ter problema de inconsistência do DB
        $inscricao = \DB::transaction(function () use ($request, $selecao) {
            $inscricao = new Inscricao;
            $inscricao->selecao_id = $selecao->id;

            // vamos salvar sem evento pois o autor ainda não está cadastrado
            $inscricao->saveQuietly();

            $inscricao->users()->attach(\Auth::user(), ['papel' => 'Autor']);

            // agora sim vamos disparar o evento
            event('eloquent.created: App\Models\Inscricao', $inscricao);

            return $inscricao;
        });

        $request->session()->flash('alert-info', 'Inscrição enviada com sucesso');
        return redirect()->route('inscricoes.show', $inscricao->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inscricao  $inscricao
     * @return \Illuminate\Http\Response
     */
    public function show(Inscricao $inscricao)
    {
        $this->authorize('inscricoes.view', $inscricao);
        \UspTheme::activeUrl('inscricoes');

        $autor = $inscricao->users()->wherePivot('papel', 'Autor')->first();
        $status_list = $inscricao->selecao->getStatusToSelect();
        $modelo = 'Inscricao';
        $max_upload_size = config('inscricoes.upload_max_filesize');
        return view('inscricoes/show', compact('autor', 'inscricao', 'status_list', 'modelo', 'max_upload_size'));
    }
}
