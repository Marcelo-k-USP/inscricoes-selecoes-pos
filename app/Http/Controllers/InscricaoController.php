<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Models\Inscricao;
use App\Models\LocalUser;
use App\Models\Selecao;
use App\Models\User;
use App\Services\RecaptchaService;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('auth')->except(['listaSelecoes', 'create', 'store']);    // exige que o usuário esteja logado, exceto para listaSelecoes, create e store
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perfil_admin = (session('perfil') == 'admin');
        $this->authorize('inscricoes.view' . ($perfil_admin ? 'Any' : 'Their'));

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
        $this->authorize('inscricoes.create');

        $request->validate(['filtro' => 'nullable|string']);

        \UspTheme::activeUrl('inscricoes/create');
        $categorias = Selecao::listarSelecoesParaNovaInscricao();          // obtém as seleções dentro das categorias
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

        \UspTheme::activeUrl('inscricoes/create');
        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
        // se for usuário logado (tanto usuário local quanto não local)...
        if (Auth::check()) {
            $user = Auth::user();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RecaptchaService $recaptcha_service)
    {
        $selecao = Selecao::find($request->selecao_id);
        $this->authorize('inscricoes.create', $selecao);

        $user_logado = Auth::check();
        if (!$user_logado) {

            // revalida o reCAPTCHA
            if (!$recaptcha_service->revalidate($request->input('g-recaptcha-response'))) {
                $request->session()->flash('alert-danger', 'Falha na validação do reCAPTCHA. Por favor, tente novamente.');

                \UspTheme::activeUrl('inscricoes/create');
                $inscricao = new Inscricao;
                $inscricao->selecao = $selecao;
                $inscricao->extras = json_encode($request->extras);    // recarrega a mesma página com os dados que o usuário preencheu antes do submit... pois o {{ old }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old }} dentro do {!! $element !!} do inscricoes.show.card-principal
                return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
            }

            // verifica se está duplicando o e-mail (pois mais pra baixo este usuário será gravado na tabela users, e não podemos permitir duplicatas)
            if (User::emailExiste($request->extras['e_mail'])) {
                $request->session()->flash('alert-danger', 'Este e-mail já está cadastrado!');

                \UspTheme::activeUrl('inscricoes/create');
                $inscricao = new Inscricao;
                $inscricao->selecao = $selecao;
                $inscricao->extras = json_encode($request->extras);    // recarrega a mesma página com os dados que o usuário preencheu antes do submit... pois o {{ old }} não funciona dentro do JSONForms.php pelo fato do blade não conseguir executar o {{ old }} dentro do {!! $element !!} do inscricoes.show.card-principal
                return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
            }
        }

        // transaction para não ter problema de inconsistência do DB
        $inscricao = \DB::transaction(function () use ($request, $selecao, $user_logado) {
            if (!$user_logado) {

                // grava o usuário na tabela local
                $user = LocalUser::create(
                    $request->extras['nome'],
                    $request->extras['e_mail'],
                    $request->senha,
                    $request->extras['celular']
                );

                // loga automaticamente o usuário
                $user->givePermissionTo('user');
                $user->last_login_at = now();
                $user->save();
                Auth::login($user, true);
                session(['perfil' => 'usuario']);
            }

            $inscricao = new Inscricao;
            $inscricao->selecao_id = $selecao->id;
            $inscricao->extras = json_encode($request->extras);

            // vamos salvar sem evento pois o autor ainda não está cadastrado
            $inscricao->saveQuietly();

            $inscricao->users()->attach(\Auth::user(), ['papel' => 'Autor']);

            // agora sim vamos disparar o evento
            event('eloquent.created: App\Models\Inscricao', $inscricao);

            return $inscricao;
        });

        $request->session()->flash('alert-danger', null);    // estranhamente, a invocação de $errors->any() no messages.errors não estava sendo suficiente para limpar os errors para o submit seguinte
        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');

        \UspTheme::activeUrl('inscricoes/create');
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

        $inscricao->extras = json_encode($request->extras);
        $inscricao->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    private function monta_compact($inscricao, $modo) {
        $data = (object) self::$data;
        $inscricao->selecao->template = JSONForms::orderTemplate($inscricao->selecao->template);
        $modelo = $inscricao;
        $tipo_modelo = 'Inscricao';
        $form = JSONForms::generateForm($modelo->selecao, $modelo);
        $max_upload_size = config('selecoes-pos.upload_max_filesize');

        return compact('data', 'modelo', 'tipo_modelo', 'form', 'modo', 'max_upload_size');
    }
}
