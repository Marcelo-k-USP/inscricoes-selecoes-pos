<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoIsencaoTaxaRequest;
use App\Mail\SolicitacaoIsencaoTaxaMail;
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
        $this->authorize('solicitacoesisencaotaxa.create');

        $selecao = Selecao::find($request->selecao_id);
        $user_logado = Auth::check();
        if ($user_logado) {

            // transaction para não ter problema de inconsistência do DB
            $solicitacaoisencaotaxa = DB::transaction(function () use ($request, $selecao) {
                $user = \Auth::user();

                // grava a solicitação de isenção de taxa
                $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
                $solicitacaoisencaotaxa->selecao_id = $selecao->id;
                $solicitacaoisencaotaxa->estado = 'Aguardando Comprovação';
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

        } else {
            // usuário não logado

            // para as validações, começa sempre com o reCAPTCHA... depois valida cada campo na ordem em que aparecem na tela

            // revalida o reCAPTCHA
            if (!$recaptcha_service->revalidate($request->input('g-recaptcha-response')))
                return $this->processa_erro_store('Falha na validação do reCAPTCHA. Por favor, tente novamente.', $selecao, $request);

            // verifica se está duplicando o e-mail (pois mais pra baixo este usuário será gravado na tabela users, e não podemos permitir duplicatas)
            if (User::emailExiste($request->extras['e_mail']))
                return $this->processa_erro_store('Este e-mail já está cadastrado!', $selecao, $request);

            // verifica se a senha é forte... não usa $request->validate porque ele voltaria para a página apagando todos os campos... pois o {{ old(...) }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old(...) }} dentro do {!! $element !!} do solicitacoesisencaotaxa.show.card-principal
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            ],[
                'password.required' => 'A senha é obrigatória!',
                'password.min' => 'A senha deve ter pelo menos 8 caracteres!',
                'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial!',
            ]);
            if ($validator->fails())
                return $this->processa_erro_store(json_decode($validator->errors())->password, $selecao, $request);

            // transaction para não ter problema de inconsistência do DB
            $solicitacaoisencaotaxa = DB::transaction(function () use ($request, $selecao) {

                // grava o usuário na tabela local
                $localuser = LocalUser::create(
                    $request->extras['nome'],
                    $request->extras['e_mail'],
                    $request->password
                );
                $localuser->save();

                // grava a solicitação de isenção de taxa
                $solicitacaoisencaotaxa = new SolicitacaoIsencaoTaxa;
                $solicitacaoisencaotaxa->selecao_id = $selecao->id;
                $solicitacaoisencaotaxa->estado = 'Aguardando Comprovação';
                $solicitacaoisencaotaxa->extras = json_encode($request->extras);
                $solicitacaoisencaotaxa->saveQuietly();      // vamos salvar sem evento pois o autor ainda não está cadastrado
                $solicitacaoisencaotaxa->users()->attach(User::find($localuser->id), ['papel' => 'Autor']);

                // gera um token e o armazena no banco de dados
                $token = Str::random(60);
                DB::table('email_confirmations')->updateOrInsert(
                    ['email' => $localuser->email],    // procura por registro com este e-mail
                    [                                  // atualiza ou insere com os dados abaixo
                        'email' => $localuser->email,
                        'token' => Hash::make($token),
                        'created_at' => now()
                    ]
                );

                // envia e-mail pedindo a confirmação do endereço de e-mail
                $passo = 'confirmação de e-mail';
                $user = $localuser;
                $email_confirmation_url = url('localusers/confirmaemail', $token);
                \Mail::to($localuser->email)
                    ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user', 'email_confirmation_url')));

                return $solicitacaoisencaotaxa;
            });

            $request->session()->flash('alert-success', 'Solicitação de isenção de taxa iniciada com sucesso<br />' .
                'Verifique seu e-mail para confirmar seu endereço de e-mail<br />' .
                'Em seguida, faça login e suba os documentos necessários para a avaliação da sua solicitação');

            \UspTheme::activeUrl('solicitacoesisencaotaxa/create');
            return redirect('/');    // volta para a tela de informações
        }
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
        if ($request->conjunto_alterado == 'estado') {
            $this->authorize('solicitacoesisencaotaxa.updateStatus', $solicitacaoisencaotaxa);

            // transaction para não ter problema de inconsistência do DB
            $solicitacaoisencaotaxa = DB::transaction(function () use ($request, $solicitacaoisencaotaxa) {

                $solicitacaoisencaotaxa->estado = $request->estado;
                $solicitacaoisencaotaxa->save();

                // envia e-mail avisando o usuário da aprovação/rejeição da solicitação de isenção de taxa
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

    public function monta_compact(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa, string $modo)
    {
        $data = (object) self::$data;
        $solicitacaoisencaotaxa->selecao->template = JSONForms::orderTemplate($solicitacaoisencaotaxa->selecao->template);
        $objeto = $solicitacaoisencaotaxa;
        $classe_nome = 'SolicitacaoIsencaoTaxa';
        $classe_nome_plural = 'solicitacoesisencaotaxa';
        $form = JSONForms::generateForm($objeto->selecao, $classe_nome, $objeto);
        $responsaveis = $objeto->selecao->programa->obterResponsaveis();
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        $motivosisencaotaxa = MotivoIsencaoTaxa::listarMotivosIsencaoTaxa();

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'responsaveis', 'max_upload_size', 'motivosisencaotaxa');
    }
}
