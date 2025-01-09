<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoIsencaoTaxaRequest;
use App\Models\LocalUser;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\User;
use App\Services\RecaptchaService;
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
        $data = self::$data;
        $objetos = SolicitacaoIsencaoTaxa::listarSolicitacoesIsencaoTaxa();
        $classe_nome = 'SolicitacaoIsencaoTaxa';
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        return view('solicitacoesisencaotaxa.index', compact('data', 'objetos', 'classe_nome', 'max_upload_size'));
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

        \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
        $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
        $solicitacaoisencaotaxa->selecao = $selecao;
        // se for usuário logado (tanto usuário local quanto não local)...
        if (Auth::check()) {
            $user = Auth::user();
            $extras = array(
                'nome' => $user->name,
                'e_mail' => $user->email,
            );
            $solicitacaoisencaotaxa->extras = json_encode($extras);
        }
        return view('solicitacoesisencaotaxa.edit', $this->monta_compact($solicitacaoisencaotaxa, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request        $request
     * @param  \App\Services\RecaptchaService  $recaptcha_service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RecaptchaService $recaptcha_service)
    {

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

    public function monta_compact(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa, string $modo)
    {
        $data = (object) self::$data;
        $solicitacaoisencaotaxa->selecao->template = JSONForms::orderTemplate($solicitacaoisencaotaxa->selecao->template);
        $objeto = $solicitacaoisencaotaxa;
        $classe_nome = 'SolicitacaoIsencaoTaxa';
        $classe_nome_plural = 'solicitacoesisencaotaxa';
        $form = JSONForms::generateForm($objeto->selecao, $objeto);
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        $motivosisencaotaxa = MotivoIsencaoTaxa::listarMotivosIsencaoTaxa();

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'max_upload_size', 'motivosisencaotaxa');
    }
}
