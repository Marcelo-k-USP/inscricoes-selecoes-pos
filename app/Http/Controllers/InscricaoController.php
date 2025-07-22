<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Jobs\AtualizaStatusSelecoes;
use App\Mail\InscricaoMail;
use App\Models\Arquivo;
use App\Models\Disciplina;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\LocalUser;
use App\Models\Nivel;
use App\Models\Orientador;
use App\Models\Parametro;
use App\Models\Programa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\TipoArquivo;
use App\Models\User;
use App\Services\BoletoService;
use App\Utils\JSONForms;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InscricaoController extends Controller
{
    protected $boletoService;

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

    public function __construct(BoletoService $boletoService)
    {
        $this->middleware('auth')->except([
            'listaSelecoesParaNovaInscricao',
            'create',
            'store'
        ]);    // exige que o usuário esteja logado, exceto para estes métodos listados
        $this->boletoService = $boletoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (in_array(session('perfil'), ['admin', 'gerente', 'docente']))
            $this->authorize('inscricoes.viewAny');
        else
            $this->authorize('inscricoes.viewTheir');

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.index', $this->monta_compact_index());
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
        AtualizaStatusSelecoes::dispatch()->onConnection('sync');
        $categorias = Selecao::listarSelecoesParaNovaInscricao();          // obtém as seleções dentro das categorias
        return view('inscricoes.listaselecoesparanovainscricao', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Selecao        $selecao
     * @param  ?\App\Models\Nivel         $nivel
     * @return \Illuminate\Http\Response
     */
    public function create(Selecao $selecao, ?Nivel $nivel = null)
    {
        $this->authorize('inscricoes.create', $selecao);

        $inscricao = new Inscricao;
        $inscricao->selecao = $selecao;
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
                'celular' => ((!Str::contains($user->telefone, 'ramal USP')) ? $user->telefone : ''),
                'e_mail' => $user->email,
            );
        } else
            $extras = array(
                'nome' => $user->name,
                'celular' => ((!Str::contains($user->telefone, 'ramal USP')) ? $user->telefone : ''),
                'e_mail' => $user->email,
            );
        if ($selecao->categoria->nome !== 'Aluno Especial')
            $extras['nivel'] = $nivel->id;
        $inscricao->extras = json_encode($extras);

        \UspTheme::activeUrl('inscricoes/create');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request        $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selecao = Selecao::find($request->selecao_id);
        $this->authorize('inscricoes.create', $selecao);

        $user = \Auth::user();

        // transaction para não ter problema de inconsistência do DB
        $inscricao = DB::transaction(function () use ($request, $user, $selecao) {

            // grava a inscrição
            $inscricao = new Inscricao;
            $inscricao->selecao_id = $selecao->id;
            $inscricao->estado = 'Aguardando Envio';
            $inscricao->extras = json_encode($request->extras);
            $inscricao->saveQuietly();      // vamos salvar sem evento pois o autor ainda não está cadastrado
            $inscricao->load('selecao');    // com isso, $inscricao->selecao é carregado
            $inscricao->users()->attach($user, ['papel' => 'Autor']);

            return $inscricao;
        });

        // agora sim vamos disparar o evento (necessário porque acima salvamos com saveQuietly)
        event('eloquent.created: App\Models\Inscricao', $inscricao);

        $request->session()->flash('alert-success', 'Envie os documentos necessários para a avaliação da sua inscrição<br />' .
            'Sem eles, sua inscrição não será avaliada!');
        \UspTheme::activeUrl('inscricoes/create');
        return redirect()->to(url('inscricoes/edit/' . $inscricao->id))->with($this->monta_compact($inscricao, 'edit', 'arquivos'));    // se fosse return view, um eventual F5 do usuário duplicaria o registro... POSTs devem ser com redirect
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
        $inscricao->selecao->atualizarStatus();
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', session('scroll')));    // repassa scroll que eventualmente veio de redirect()->to(url(
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
        \UspTheme::activeUrl('inscricoes');

        if ($request->input('acao', null) == 'envio') {
            $this->authorize('inscricoes.update', $inscricao);

            $extras = json_decode(stripslashes($inscricao->extras), true);
            if ($inscricao->todosArquivosRequeridosPresentes($extras['nivel'] ?? null)) {

                $disciplinas_id = (isset($extras['disciplinas']) ? $extras['disciplinas'] : []);
                if (($inscricao->selecao->categoria->nome != 'Aluno Especial') || (count($disciplinas_id) > 0)) {

                    $inscricao->estado = 'Enviada';
                    $inscricao->save();

                    $info_adicional = '';
                    $user = \Auth::user();
                    if ($inscricao->selecao->tem_taxa && !$user->solicitacoesIsencaoTaxa()->where('selecao_id', $inscricao->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->exists()) {

                        $inscricao->load('arquivos');    // atualiza a relação de arquivos da inscrição, pois foi gerado mais um arquivo (boleto) para ela no evento disparado pelo $inscricao->save() acima
                        $inscricao->save();

                        $this->processa_disciplinas_alteradas($inscricao, $disciplinas_id);

                        $info_adicional = ($inscricao->selecao->categoria->nome !== 'Aluno Especial' ? ' e seu boleto foi enviado, não deixe de pagá-lo' : ((count($disciplinas_id) == 1) ? ' e seu boleto foi enviado, não deixe de pagá-lo' : ' e seus boletos foram enviados, não deixe de pagá-los'));
                    }

                    $request->session()->flash('alert-success', 'Sua inscrição foi enviada' . $info_adicional);
                    \UspTheme::activeUrl('inscricoes');
                    return view('inscricoes.index', $this->monta_compact_index());
                } else {
                    $request->session()->flash('alert-danger', 'É necessário antes escolher a(s) disciplina(s)');
                    \UspTheme::activeUrl('inscricoes');
                    return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
                }
            } else {
                $request->session()->flash('alert-danger', 'É necessário antes enviar todos os documentos exigidos');
                \UspTheme::activeUrl('inscricoes');
                return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
            }
        }

        if ($request->conjunto_alterado == 'estado') {
            $this->authorize('inscricoes.updateStatus', $inscricao);

            $inscricao->estado = $request->estado;
            $inscricao->save();

            $request->session()->flash('alert-success', 'Estado da inscrição alterado com sucesso');

        } else {
            $this->authorize('inscricoes.update', $inscricao);

            $extras = json_decode($inscricao->extras, true);
            if (isset($extras['disciplinas']))
                $request->merge(['extras' => array_merge($request->input('extras', []), ['disciplinas' => $extras['disciplinas']])]);    // pelo fato de vir do card-principal, $request->extras não vem com as disciplinas... então precisamos recuperá-las a partir de $extras
            $inscricao->extras = json_encode($request->input('extras'));
            $inscricao->save();

            $request->session()->flash('alert-success', 'Inscrição alterada com sucesso');
        }

        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    private function processa_disciplinas_alteradas(Inscricao $inscricao, $disciplinas_id)
    {
        if ($inscricao->selecao->categoria->nome == 'Aluno Especial') {

            // transaction para não ter problema de inconsistência do DB
            $arquivos = DB::transaction(function () use ($inscricao, $disciplinas_id) {

                // obtém o conjunto de disciplinas do envio anterior
                $disciplinas_sigla_anterior = $inscricao->arquivos()->whereHas('tipoarquivo', function ($query) { $query->where('nome', 'Boleto(s) de Pagamento da Inscrição'); })->pluck('disciplina')->toArray();
                $disciplinas_id_anterior = Disciplina::whereIn('sigla', $disciplinas_sigla_anterior)->pluck('id')->toArray();

                // marca como desinscritas as disciplinas das quais o candidato se desinscreveu
                $tipoarquivo_boletodisciplinasdesinscritas = TipoArquivo::where('classe_nome', 'Inscrições')->where('nome', 'Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas')->first();
                foreach (array_diff($disciplinas_id_anterior, $disciplinas_id) as $disciplina_id_desinscrita) {
                    $disciplina = Disciplina::find($disciplina_id_desinscrita);
                    foreach ($inscricao->arquivos()->whereHas('tipoarquivo', function ($query) { $query->where('nome', 'Boleto(s) de Pagamento da Inscrição'); })->where('disciplina', $disciplina->sigla)->get() as $arquivo) {
                        $inscricao->arquivos()->updateExistingPivot(
                            $arquivo->id,                                                                   // estranhamente, o Laravel precisa que eu passe o arquivo_id aqui, mesmo que eu tenha começado este comando com $inscricao (ou seja, ele deveria saber qual é a inscrição)
                            ['tipo' => 'Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas']    // atualiza o tipo do arquivo para "Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas"
                        );
                        $arquivo->tipoarquivo_id = $tipoarquivo_boletodisciplinasdesinscritas->id;          // atualiza o tipo do arquivo para "Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas"
                        $arquivo->save();
                    }
                }

                // gera boletos para as novas disciplinas deste reenvio
                $arquivos = [];
                foreach (array_diff($disciplinas_id, $disciplinas_id_anterior) as $disciplina_id_nova) {
                    $disciplina = Disciplina::find($disciplina_id_nova);
                    $arquivos[] = $this->boletoService->gerarBoleto($inscricao, $disciplina->sigla);
                }

                return $arquivos;
            });

            if (!empty($arquivos)) {
                // envia e-mail para o candidato com o(s) boleto(s)
                // envio do e-mail "12" do README.md
                $passo = 'boleto(s) - disciplinas alteradas';
                $user = \Auth::user();
                $email_secaoinformatica = Parametro::first()->email_secaoinformatica;
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'arquivos', 'email_secaoinformatica')));
            }
        }
    }

    /**
     * Adiciona uma disciplina relacionada à inscrição
     * autorizado a qualquer um que tenha acesso à inscrição
     */
    public function storeDisciplina(Request $request, Inscricao $inscricao)
    {
        $this->authorize('inscricoes.update', $inscricao);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Disciplina obrigatória',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $inscricao) {

            $info_adicional = '';
            $disciplina = Disciplina::where('id', $request->id)->first();

            $extras = json_decode($inscricao->extras, true);
            $disciplinas_id = (isset($extras['disciplinas']) ? $extras['disciplinas'] : []);
            $existia = is_array($disciplinas_id) && in_array($request->id, $disciplinas_id);

            if (!$existia) {
                $extras['disciplinas'][] = $request->id;
                $inscricao->extras = json_encode($extras);
                $inscricao->save();

                // se já havia enviado a inscrição, avisa para reenviá-la
                if ($inscricao->estado == 'Enviada')
                    $info_adicional = '<br />Reenvie esta inscrição para gerar ' . ((count($extras['disciplinas']) == 1) ? 'novo boleto' : 'novos boletos');
            }

            return ['disciplina' => $disciplina, 'existia' => $existia, 'info_adicional' => $info_adicional];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'A disciplina ' . $db_transaction['disciplina']->sigla . ' - ' . $db_transaction['disciplina']->nome . ' foi adicionada à essa inscrição.' . $db_transaction['info_adicional']);
        else
            $request->session()->flash('alert-info', 'A disciplina ' . $db_transaction['disciplina']->sigla . ' - ' . $db_transaction['disciplina']->nome . ' já estava vinculada à essa inscrição.');
        \UspTheme::activeUrl('inscricoes');
        return redirect()->to(url('inscricoes/edit/' . $inscricao->id))->with($this->monta_compact($inscricao, 'edit', 'disciplinas'));    // se fosse return view, um eventual F5 do usuário duplicaria o registro... POSTs devem ser com redirect
    }

    /**
     * Remove uma disciplina relacionada à inscrição
     */
    public function destroyDisciplina(Request $request, Inscricao $inscricao, Disciplina $disciplina)
    {
        $this->authorize('inscricoes.update', $inscricao);

        $extras = json_decode($inscricao->extras, true);
        $disciplinas_id = (isset($extras['disciplinas']) ? $extras['disciplinas'] : []);
        $indice = array_search($disciplina->id, $disciplinas_id);

        if ($indice !== false) {
            unset($extras['disciplinas'][$indice]);
            $inscricao->extras = json_encode($extras);
            $inscricao->save();
        }

        // se já havia enviado a inscrição, avisa para reenviá-la
        $info_adicional = '';
        if ($inscricao->estado == 'Enviada')
            $info_adicional = '<br />Reenvie esta inscrição para gerar ' . ((count($extras['disciplinas']) == 1) ? 'novo boleto' : 'novos boletos');

        $request->session()->flash('alert-success', 'A disciplina ' . $disciplina->sigla . ' - '. $disciplina->nome . ' foi removida dessa inscrição.' . $info_adicional);
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', 'disciplinas'));
    }

    /**
     * Gera o(s) boleto(s) para a inscrição
     */
    public function geraBoletos(Request $request, Inscricao $inscricao)
    {
        if ($inscricao->selecao->categoria->nome !== 'Aluno Especial') {
            // gera o boleto da inscrição
            if (empty($this->boletoService->gerarBoleto($inscricao)['nome_original'])) {
                $request->session()->flash('alert-danger', 'Não foi possível gerar o boleto para essa inscrição');
                \UspTheme::activeUrl('inscricoes');
                return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
            }
        } else
            // gera um boleto para cada disciplina solicitada
            foreach ($request->disciplinas as $sigla => $valor)
                if (empty($this->boletoService->gerarBoleto($inscricao, $sigla)['nome_original'])) {
                    $request->session()->flash('alert-danger', 'Não foi possível gerar o boleto da disciplina ' . $sigla . ' para essa inscrição<br />' .
                        'A geração do(s) boleto(s) foi abortada');
                    \UspTheme::activeUrl('inscricoes');
                    return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
                }

        $request->session()->flash('alert-success', ($inscricao->selecao->categoria->nome !== 'Aluno Especial' ? 'O boleto foi gerado com sucesso' : 'O(s) boleto(s) foi(ram) gerado(s) com sucesso'));
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', 'arquivos'));
    }

    /**
     * Envia um boleto da inscrição
     */
    public function enviaBoleto(Request $request, Inscricao $inscricao, Arquivo $arquivo)
    {
        if (!$arquivo || !$arquivo->inscricoes->contains($inscricao)) {
            $request->session()->flash('alert-danger', 'Esse documento não existe ou não pertence a essa inscrição');
            \UspTheme::activeUrl('inscricoes');
            return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
        }

        // envia e-mail para o candidato com o boleto
        // envio do e-mail "13" do README.md
        $passo = 'boleto - envio manual';
        $user = $inscricao->pessoas('Autor');
        $arquivo->conteudo = base64_encode(Storage::get($arquivo->caminho));
        \Mail::to($user->email)
            ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'arquivo')));

        $request->session()->flash('alert-success', 'O boleto foi enviado com sucesso');
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', 'arquivos'));
    }

    public function monta_compact_index()
    {
        $data = self::$data;
        $objetos = Inscricao::listarInscricoes();
        foreach ($objetos as $objeto) {
            $extras = json_decode($objeto->extras, true);
            $objeto->linha_pesquisa = (isset($extras['linha_pesquisa']) ? (LinhaPesquisa::where('id', $extras['linha_pesquisa'])->first()->nome ?? null) : null);
            $objeto->disciplinas = (isset($extras['disciplinas']) ? (Disciplina::whereIn('id', $extras['disciplinas'])->orderBy('sigla')->get()->map(function ($disciplina) {
                return $disciplina->sigla . ' - ' . $disciplina->nome;
            })->implode(',<br />')) : null);
        }
        $classe_nome = 'Inscricao';
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');
        $niveis = Nivel::all();

        return compact('data', 'objetos', 'classe_nome', 'max_upload_size', 'niveis');
    }

    public function monta_compact(Inscricao $inscricao, string $modo, ?string $scroll = null)
    {
        $data = (object) self::$data;
        $inscricao->selecao->template = JSONForms::orderTemplate($inscricao->selecao->template);
        $objeto = $inscricao;
        $classe_nome = 'Inscricao';
        $classe_nome_plural = 'inscricoes';
        $form = JSONForms::generateForm($objeto->selecao, $classe_nome, $objeto);
        $responsaveis = $objeto->selecao->programa?->obterResponsaveis() ?? (new Programa())->obterResponsaveis();
        $extras = json_decode($objeto->extras, true);
        $inscricao_disciplinas = ((isset($extras['disciplinas']) && is_array($extras['disciplinas'])) ? Disciplina::whereIn('id', $extras['disciplinas'])->orderBy('sigla')->get() : collect());
        $disciplinas = Disciplina::listarDisciplinas($objeto->selecao);
        $nivel = (isset($extras['nivel']) ? Nivel::where('id', $extras['nivel'])->first()->nome : '');
        $objeto->tiposarquivo = TipoArquivo::obterTiposArquivoDaSelecao('Inscricao', ($objeto->selecao->categoria?->nome == 'Aluno Especial' ? new Collection() : collect([['nome' => $nivel]])), $objeto->selecao)
            ->filter(function ($tipoarquivo) use ($inscricao) { return (!in_array($tipoarquivo->nome, ['Boleto(s) de Pagamento da Inscrição', 'Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas'])) || $inscricao->selecao->tem_taxa; })
            ->sortBy(function ($tipoarquivo) { return in_array($tipoarquivo->nome, ['Boleto(s) de Pagamento da Inscrição', 'Boleto(s) de Pagamento da Inscrição - Disciplinas Desinscritas']) ? 1 : 0; });
        $tiposarquivo_selecao = TipoArquivo::obterTiposArquivoPossiveis('Selecao', null, $objeto->selecao->programa_id)
            ->filter(function ($tipoarquivo) use ($inscricao) { return ($tipoarquivo->nome !== 'Normas para Isenção de Taxa') || $inscricao->selecao->tem_taxa; });
        $solicitacaoisencaotaxa_aprovada = $inscricao->pessoas('Autor')?->solicitacoesIsencaoTaxa()?->where('selecao_id', $objeto->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->first();
        $disciplinas_sem_boleto = [];
        if ($inscricao->selecao->categoria->nome == 'Aluno Especial')
            foreach ($inscricao_disciplinas as $disciplina)
                if ($inscricao->arquivos->filter(fn($a) => ($a->pivot->tipo == 'Boleto(s) de Pagamento da Inscrição') && str_contains(strtolower($a->nome_original), strtolower($disciplina->sigla)))->count() == 0)
                    $disciplinas_sem_boleto[] = $disciplina;
        $inscricao->disciplinas_sem_boleto = $disciplinas_sem_boleto;
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'responsaveis', 'inscricao_disciplinas', 'disciplinas', 'nivel', 'tiposarquivo_selecao', 'solicitacaoisencaotaxa_aprovada', 'max_upload_size', 'scroll');
    }
}
