<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Mail\InscricaoMail;
use App\Models\Inscricao;
use App\Models\LocalUser;
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
        $this->middleware('auth')->except([
            'listaSelecoesParaNovaInscricao',
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
        $this->authorize('inscricoes.view' . ($perfil_admin_ou_gerente ? 'Any' : 'Their'));

        \UspTheme::activeUrl('inscricoes');
        $data = self::$data;
        $objetos = Inscricao::listarInscricoes();
        $classe_nome = 'Inscricao';
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
        return view('inscricoes.index', compact('data', 'objetos', 'classe_nome', 'max_upload_size'));
    }

    /**
     * Mostra lista de seleções e respectivas categorias
     * para selecionar e criar nova inscrição
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function listaSelecoesParaNovaInscricao(Request $request)
    {
        $this->authorize('inscricoes.create');

        $request->validate(['filtro' => 'nullable|string']);

        \UspTheme::activeUrl('inscricoes/create');
        $categorias = Selecao::listarSelecoesParaNovaInscricao();          // obtém as seleções dentro das categorias
        return view('inscricoes.listaselecoesparanovainscricao', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Selecao        $selecao
     * @return \Illuminate\Http\Response
     */
    public function create(Selecao $selecao)
    {
        $this->authorize('inscricoes.create');

        \UspTheme::activeUrl('inscricoes/create');
        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
        // se for usuário logado (tanto usuário local quanto não local)...
        if (Auth::check()) {
            $user = Auth::user();

            // se o usuário já solicitou isenção de taxa para esta seleção...
            $solicitacaoisencaotaxa = $user->solicitacoesIsencaoTaxa()?->where('selecao_id', $selecao->id)->first();
            if ($solicitacaoisencaotaxa) {
                $solicitacaoisencaotaxa_extras = json_decode($solicitacaoisencaotaxa->extras, true);
                $extras = array(
                    'nome' => $user->name,
                    'tipo_de_documento' => $solicitacaoisencaotaxa_extras['tipo_de_documento'],
                    'numero_do_documento' => $solicitacaoisencaotaxa_extras['numero_do_documento'],
                    'cpf' => $solicitacaoisencaotaxa_extras['cpf'],
                    'e_mail' => $user->email,
                );
            } else
                $extras = array(
                    'nome' => $user->name,
                    'e_mail' => $user->email,
                );
            $inscricao->extras = json_encode($extras);
        }

        return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
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
        $this->authorize('inscricoes.create');

        $selecao = Selecao::find($request->selecao_id);
        $user_logado = Auth::check();
        if ($user_logado) {

            // transaction para não ter problema de inconsistência do DB
            $inscricao = DB::transaction(function () use ($request, $selecao) {
                $user = \Auth::user();

                // grava a inscrição
                $inscricao = new Inscricao;
                $inscricao->selecao_id = $selecao->id;
                $inscricao->estado = 'Aguardando Documentação';
                $inscricao->extras = json_encode($request->extras);
                $inscricao->saveQuietly();      // vamos salvar sem evento pois o autor ainda não está cadastrado
                $inscricao->load('selecao');    // com isso, $inscricao->selecao é carregado
                $inscricao->users()->attach($user, ['papel' => 'Autor']);

                return $inscricao;
            });

            $request->session()->flash('alert-success', 'Inscrição iniciada com sucesso<br />' .
                'Não deixe de subir os documentos necessários para a avaliação da sua inscrição');

            \UspTheme::activeUrl('inscricoes/create');
            return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));

        } else {
            // usuário não logado

            // para as validações, começa sempre com o reCAPTCHA... depois valida cada campo na ordem em que aparecem na tela

            // revalida o reCAPTCHA
            if (!$recaptcha_service->revalidate($request->input('g-recaptcha-response')))
                return $this->processa_erro_store('Falha na validação do reCAPTCHA. Por favor, tente novamente.', $selecao, $request);

            // verifica se está duplicando o e-mail (pois mais pra baixo este usuário será gravado na tabela users, e não podemos permitir duplicatas)
            if (User::emailExiste($request->extras['e_mail']))
                return $this->processa_erro_store('Este e-mail já está cadastrado!', $selecao, $request);

            // verifica se a senha é forte... não usa $request->validate porque ele voltaria para a página apagando todos os campos... pois o {{ old(...) }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old(...) }} dentro do {!! $element !!} do inscricoes.show.card-principal
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
            $inscricao = DB::transaction(function () use ($request, $selecao) {

                // grava o usuário na tabela local
                $localuser = LocalUser::create(
                    $request->extras['nome'],
                    $request->extras['e_mail'],
                    $request->password,
                    $request->extras['celular']
                );
                $localuser->save();

                // grava a inscrição
                $inscricao = new Inscricao;
                $inscricao->selecao_id = $selecao->id;
                $inscricao->estado = 'Aguardando Documentação';
                $inscricao->extras = json_encode($request->extras);
                $inscricao->saveQuietly();      // vamos salvar sem evento pois o autor ainda não está cadastrado
                $inscricao->load('selecao');    // com isso, $inscricao->selecao é carregado
                $inscricao->users()->attach(User::find($localuser->id), ['papel' => 'Autor']);

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
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'email_confirmation_url')));

                return $inscricao;
            });

            $request->session()->flash('alert-success', 'Inscrição iniciada com sucesso<br />' .
                'Verifique seu e-mail para confirmar seu endereço de e-mail<br />' .
                'Em seguida, faça login e suba os documentos necessários para a avaliação da sua inscrição');

            \UspTheme::activeUrl('inscricoes/create');
            return redirect('/');    // volta para a tela de informações
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Inscricao      $inscricao
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Inscricao $inscricao)
    {
        $this->authorize('inscricoes.view', $inscricao);    // este 1o passo da edição é somente um show, não chega a haver um update

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Inscricao      $inscricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscricao $inscricao)
    {
        if ($request->conjunto_alterado == 'estado') {
            $this->authorize('inscricoes.updateStatus', $inscricao);

            // transaction para não ter problema de inconsistência do DB
            $inscricao = DB::transaction(function () use ($request, $inscricao) {

                $inscricao->estado = $request->estado;
                $inscricao->save();

                // envia e-mail avisando o usuário da aprovação/rejeição da inscrição
                if (in_array($inscricao->estado, ['Aprovada', 'Rejeitada'])) {
                    $passo = (($inscricao->estado == 'Aprovada') ? 'aprovação' : 'rejeição');
                    $user = $inscricao->users()->wherePivot('papel', 'Autor')->first();
                    \Mail::to($user->email)
                        ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user')));
                }
                return $inscricao;
            });

            $request->session()->flash('alert-success', 'Estado da inscrição alterado com sucesso');

        } else {
            $this->authorize('inscricoes.update', $inscricao);

            $inscricao->extras = json_encode($request->extras);
            $inscricao->save();

            $request->session()->flash('alert-success', 'Inscrição alterada com sucesso');
        }

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
}

    private function processa_erro_store(string|array $msgs, Selecao $selecao, Request $request)
    {
        if (is_array($msgs))
            $msgs = implode('<br />', $msgs);
        $request->session()->flash('alert-danger', $msgs);

        \UspTheme::activeUrl('inscricoes/create');
        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
        $inscricao->extras = json_encode($request->extras);    // recarrega a mesma página com os dados que o usuário preencheu antes do submit... pois o {{ old }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old }} dentro do {!! $element !!} do inscricoes.show.card-principal
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
    }

    public function monta_compact(Inscricao $inscricao, string $modo)
    {
        $data = (object) self::$data;
        $inscricao->selecao->template = JSONForms::orderTemplate($inscricao->selecao->template);
        $objeto = $inscricao;
        $classe_nome = 'Inscricao';
        $classe_nome_plural = 'inscricoes';
        $form = JSONForms::generateForm($objeto->selecao, $classe_nome, $objeto);
        $solicitacaoisencaotaxa_aprovada = \Auth::user()?->solicitacoesIsencaoTaxa()?->where('selecao_id', $objeto->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->first();
        $max_upload_size = config('selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'solicitacaoisencaotaxa_aprovada', 'max_upload_size');
    }
}
