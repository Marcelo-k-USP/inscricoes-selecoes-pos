<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoIsencaoTaxaRequest;
use App\Mail\SolicitacaoIsencaoTaxaMail;
use App\Models\LocalUser;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Programa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\User;
use App\Utils\JSONForms;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Uspdev\Replicado\Pessoa;

class SolicitacaoIsencaoTaxaController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Solicitações de Isenção de Taxa',
        'url' => 'solicitacoesisencaotaxa',     // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\SolicitacaoIsencaoTaxa',
    ];

    public function __construct()
    {
        $this->middleware('auth')->except([
            'listaSelecoesParaSolicitacaoIsencaoTaxa',
            'create',
            'store'
        ]);    // exige que o usuário esteja logado, exceto para estes métodos listados
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perfil_admin_ou_gerente = ((session('perfil') == 'admin') || (session('perfil') == 'gerente'));
        $this->authorize('solicitacoesisencaotaxa.view' . ($perfil_admin_ou_gerente ? 'Any' : 'Their'));

        \UspTheme::activeUrl('solicitacoesisencaotaxa');
        return view('solicitacoesisencaotaxa.index', $this->monta_compact_index());
    }

    /**
     * Mostra lista de seleções e respectivas categorias
     * para solicitar isenção de taxa
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function listaSelecoesParaSolicitacaoIsencaoTaxa(Request $request)
    {
        $this->authorize('solicitacoesisencaotaxa.create');

        $request->validate(['filtro' => 'nullable|string']);

        \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
        $categorias = Selecao::listarSelecoesParaSolicitacaoIsencaoTaxa();          // obtém as seleções dentro das categorias
        return view('solicitacoesisencaotaxa.listaselecoesparasolicitacaoisencaotaxa', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Selecao        $selecao
     * @return \Illuminate\Http\Response
     */
    public function create(Selecao $selecao)
    {
        $this->authorize('solicitacoesisencaotaxa.create');

        $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
        $solicitacaoisencaotaxa->selecao = $selecao;
        $user = Auth::user();
        $extras = array(
            'nome' => $user->name,
            'e_mail' => $user->email,
        );
        $solicitacaoisencaotaxa->extras = json_encode($extras);

        \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request        $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('solicitacoesisencaotaxa.create');

        // transaction para não ter problema de inconsistência do DB
        $solicitacaoisencaotaxa = DB::transaction(function () use ($request) {
            $user = \Auth::user();
            $selecao = Selecao::find($request->selecao_id);

            // grava a solicitação de isenção de taxa
            $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
            $solicitacaoisencaotaxa->selecao_id = $selecao->id;
            $solicitacaoisencaotaxa->estado = 'Aguardando Envio';
            $solicitacaoisencaotaxa->extras = json_encode($request->extras);
            $solicitacaoisencaotaxa->saveQuietly();      // vamos salvar sem evento pois o autor ainda não está cadastrado
            $solicitacaoisencaotaxa->load('selecao');    // com isso, $solicitacaoisencaotaxa->selecao é carregado
            $solicitacaoisencaotaxa->users()->attach($user, ['papel' => 'Autor']);

            return $solicitacaoisencaotaxa;
        });

        $request->session()->flash('alert-success', 'Solicitação de isenção de taxa iniciada com sucesso<br />' .
            'Não deixe de subir os documentos necessários para a avaliação da sua solicitação');

        \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request            $request
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        $this->authorize('solicitacoesisencaotaxa.view', $solicitacaoisencaotaxa);    // este 1o passo da edição é somente um show, não chega a haver um update

        \UspTheme::activeUrl('solicitacoesisencaotaxa');
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request            $request
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        if ($request->input('acao', null) == 'envio') {
            if ($solicitacaoisencaotaxa->todosArquivosRequeridosPresentes()) {

                $solicitacaoisencaotaxa->estado = 'Isenção de Taxa Solicitada';
                $solicitacaoisencaotaxa->save();

                // envia e-mails avisando o serviço de pós-graduação sobre a solicitação da isenção de taxa
                $passo = 'realização';
                $user = \Auth::user();
                foreach (collect((new Programa)->obterResponsaveis())->firstWhere('funcao', 'Serviço de Pós-Graduação')['users'] as $servicoposgraduacao) {
                    $servicoposgraduacao_nome = Pessoa::obterNome($servicoposgraduacao->codpes);
                    \Mail::to($servicoposgraduacao->email)
                        ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user', 'servicoposgraduacao_nome')));
                }

                $request->session()->flash('alert-success', 'Sua solicitação de isenção de taxa foi enviada');
                return view('solicitacoesisencaotaxa.index', $this->monta_compact_index());

            } else {
                $request->session()->flash('alert-success', 'É necessário antes enviar todos os documentos exigidos');
                return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'edit'));
            }
        }

        if ($request->conjunto_alterado == 'estado') {
            $this->authorize('solicitacoesisencaotaxa.updateStatus', $solicitacaoisencaotaxa);

            // transaction para não ter problema de inconsistência do DB
            $solicitacaoisencaotaxa = DB::transaction(function () use ($request, $solicitacaoisencaotaxa) {

                $solicitacaoisencaotaxa->estado = $request->estado;
                $solicitacaoisencaotaxa->save();

                // envia e-mail avisando o candidato da aprovação/rejeição da solicitação de isenção de taxa
                if (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Aprovada', 'Isenção de Taxa Rejeitada'])) {
                    $passo = (($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Aprovada') ? 'aprovação' : 'rejeição');
                    $user = $solicitacaoisencaotaxa->users()->wherePivot('papel', 'Autor')->first();
                    \Mail::to($user->email)
                        ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user')));
                }
                return $solicitacaoisencaotaxa;
            });

            $request->session()->flash('alert-success', 'Estado da solicitação de isenção de taxa alterado com sucesso');

        } else {
            $this->authorize('solicitacoesisencaotaxa.update', $solicitacaoisencaotaxa);

            $solicitacaoisencaotaxa->extras = json_encode($request->extras);
            $solicitacaoisencaotaxa->save();

            $request->session()->flash('alert-success', 'Solicitação de isenção de taxa alterada com sucesso');
        }

        \UspTheme::activeUrl('solicitacoesisencaotaxa');
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'edit'));
    }

    private function processa_erro_store(string|array $msgs, Selecao $selecao, Request $request)
    {
        if (is_array($msgs))
            $msgs = implode('<br />', $msgs);
        $request->session()->flash('alert-danger', $msgs);

        \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
        $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
        $solicitacaoisencaotaxa->selecao = $selecao;
        $solicitacaoisencaotaxa->extras = json_encode($request->extras);    // recarrega a mesma página com os dados que o usuário preencheu antes do submit... pois o {{ old }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old }} dentro do {!! $element !!} do solicitacoesisencaotaxa.show.card-principal
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'create'));
    }

    public function monta_compact_index()
    {
        $data = self::$data;
        $objetos = SolicitacaoIsencaoTaxa::listarSolicitacoesIsencaoTaxa();
        $classe_nome = 'SolicitacaoIsencaoTaxa';
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objetos', 'classe_nome', 'max_upload_size');
    }

    public function monta_compact(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa, string $modo)
    {
        $data = (object) self::$data;
        $solicitacaoisencaotaxa->selecao->template = JSONForms::orderTemplate($solicitacaoisencaotaxa->selecao->template);
        $objeto = $solicitacaoisencaotaxa;
        $classe_nome = 'SolicitacaoIsencaoTaxa';
        $classe_nome_plural = 'solicitacoesisencaotaxa';
        $form = JSONForms::generateForm($objeto->selecao, $classe_nome, $objeto);
        $responsaveis = $objeto->selecao->programa?->obterResponsaveis() ?? (new Programa())->obterResponsaveis();
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'responsaveis', 'max_upload_size');
    }
}
