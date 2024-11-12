<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Selecao;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SelecaoController extends Controller
{

    // crud generico
    protected $data = [
        'title' => 'Seleções',
        'url' => 'selecoes', // caminho da rota do resource
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

        $data = (object) $this->data;
        $selecoes = Selecao::listarSelecoes();
        $modelo = 'Selecao';
        $max_upload_size = config('inscricoes.upload_max_filesize');
        return view('selecoes.index', compact('data', 'selecoes', 'modelo', 'max_upload_size'));
    }

    /**
     * Criar nova seleção
     */
    public function store(SelecaoRequest $request)
    {
        # Para criar uma nova seleção precisamos do processo para autorizar
        $processo = Processo::find($request->processo_id);
        $this->authorize('selecoes.create', $processo);

        $selecao = Selecao::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $selecao->id);
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

        $atualizacao = [];

        // nome
        if ($selecao->nome != $request->nome && !empty($request->nome)) {
            // guardando os dados antigos em log para auditoria
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Nome antigo: ' . $selecao->nome . ' - Novo nome: ' . $request->nome);
            array_push($atualizacao, 'nome');
            $selecao->nome = $request->nome;
        }
        
        // estado
        if ($selecao->estado != $request->estado && !empty($request->estado)) {
            array_push($atualizacao, 'estado');
            $selecao->estado = $request->estado;
        }

        // descrição
        if ($selecao->descricao != $request->descricao && !empty($request->descricao)) {
            // guardando os dados antigos em log para auditoria
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Descrição antiga: ' . $selecao->descricao . ' - Nova descrição: ' . $request->descricao);
            array_push($atualizacao, 'descrição');
            $selecao->descricao = $request->descricao;
        }

        // processo_id
        if ($selecao->processo_id != $request->processo_id && !empty($request->processo_id)) {
            // guardando os dados antigos em log para auditoria
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Processo antigo: ' . $selecao->processo_id . ' - Novo processo: ' . $request->processo_id);
            array_push($atualizacao, 'processo_id');
            $selecao->processo_id = $request->processo_id;
        }
        
        $selecao->save();
        
        // arquivos
        //     verifica se tem arquivos novos e adiciona
        foreach ($request->arquivos as $request_arquivo) {
            $achou = false;
            foreach ($selecao->arquivos as $selecao_arquivo)
                if ($request_arquivo->id == $selecao_arquivo->id) {
                    $achou = true;
                    break;
                }

            if (!$achou)
                $selecao->arquivos->attach($request_arquivo->id, ['tipo' => $request_arquivo->tipo]);
        }
        //     verifica se algum arquivo foi removido e remove
        foreach ($selecao->arquivos as $selecao_arquivo) {
            $achou = false;
            foreach ($request->arquivos as $request_arquivo)
                if ($selecao_arquivo->id == $request_arquivo->id) {
                    $achou = true;
                    break;
                }
            
            if (!$achou)
                $selecao->arquivos->detach($request_arquivo->id);
        }
        // falta gravar o conteúdo do arquivo no servidor também, não só o código acima de gravar no banco de dados as informações sobre ele

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);
        \UspTheme::activeUrl('selecoes');

        if ($request->ajax()) {
            return $selecao;
        } else {
            $data = (object) $this->data;
            $modelo = 'Selecao';
            $max_upload_size = config('inscricoes.upload_max_filesize');
            return view('selecoes.show', compact('selecao', 'data', 'modelo', 'max_upload_size'));
        }
    }
}
