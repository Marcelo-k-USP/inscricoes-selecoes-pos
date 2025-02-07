<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Mail\InscricaoMail;
use App\Models\Disciplina;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\LocalUser;
use App\Models\Nivel;
use App\Models\Orientador;
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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Uspdev\Replicado\Pessoa;

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
        if ((session('perfil') == 'admin') || (session('perfil') == 'gerente'))
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
        $categorias = Selecao::listarSelecoesParaNovaInscricao();          // obtém as seleções dentro das categorias
        $niveis = Nivel::all();                                            // obtém todos os níveis cadastrados
        return view('inscricoes.listaselecoesparanovainscricao', compact('categorias', 'niveis'));
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
        $this->authorize('inscricoes.create');

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
        $this->authorize('inscricoes.create');

        // transaction para não ter problema de inconsistência do DB
        $inscricao = DB::transaction(function () use ($request) {
            $user = \Auth::user();
            $selecao = Selecao::find($request->selecao_id);

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

        $request->session()->flash('alert-success', 'Inscrição iniciada com sucesso<br />' .
            'Não deixe de subir os documentos necessários para a avaliação da sua inscrição');
        \UspTheme::activeUrl('inscricoes/create');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
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
        \UspTheme::activeUrl('inscricoes');

        if ($request->input('acao', null) == 'envio') {
            if ($inscricao->todosArquivosRequeridosPresentes()) {

                $extras = json_decode(stripslashes($inscricao->extras), true);
                $disciplinas_id = (isset($extras['disciplinas']) ? $extras['disciplinas'] : []);
                if (($inscricao->selecao->categoria->nome != 'Aluno Especial') || (count($disciplinas_id) > 0)) {

                    $inscricao->estado = 'Enviada';
                    $inscricao->save();

                    $info_adicional = '';
                    $user = \Auth::user();
                    if (!$user->solicitacoesIsencaoTaxa()->where('selecao_id', $inscricao->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->exists()) {

                        $passo = 'boleto(s)';
                        $papel = 'Candidato';
                        if ($inscricao->selecao->categoria->nome !== 'Aluno Especial') {
                            // envia e-mail para o candidato com o boleto
                            $arquivos = [[
                                'nome' => 'boleto.pdf',
                                'conteudo' => $this->boletoService->gerarBoleto($inscricao),
                            ]];
                            \Mail::to($user->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'papel', 'arquivos')));

                            $inscricao->load('arquivos');         // atualiza a relação de arquivos da inscrição, pois foi gerado mais um arquivo (boleto) para ela
                            $inscricao->save();

                            $info_adicional = ' e seu boleto foi enviado, não deixe de pagá-lo';
                        } else {
                            // envia e-mail para o candidato com o(s) boleto(s)
                            $arquivos = [];
                            $disciplinas = Disciplina::whereIn('id', array_values($disciplinas_id))->get();
                            foreach ($disciplinas as $disciplina)
                                $arquivos[] = [
                                    'nome' => 'boleto_' . strtolower($disciplina->sigla) . '.pdf',
                                    'conteudo' => $this->boletoService->gerarBoleto($inscricao, ' - disciplina ' . $disciplina->sigla),
                                ];
                            \Mail::to($user->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'papel', 'arquivos')));

                            $inscricao->load('arquivos');         // atualiza a relação de arquivos da inscrição, pois foram gerados mais arquivos (boletos) para ela
                            $inscricao->save();

                            $info_adicional = ((count($disciplinas) == 1) ? ' e seu boleto foi enviado, não deixe de pagá-lo' : ' e seus boletos foram enviados, não deixe de pagá-los');
                        }
                    }

                    $passo = 'realização';
                    if ($inscricao->selecao->categoria->nome !== 'Aluno Especial')
                        // envia e-mails avisando os secretários do programa da seleção da inscrição sobre a realização da inscrição
                        foreach (collect($inscricao->selecao->programa->obterResponsaveis())->firstWhere('funcao', 'Secretários(as) do Programa')['users'] as $secretario) {
                            $responsavel_nome = Pessoa::obterNome($secretario->codpes);
                            \Mail::to($secretario->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));
                        }
                    else
                        // envia e-mails avisando o serviço de pós-graduação sobre a realização da inscrição
                        foreach (collect((new Programa)->obterResponsaveis())->firstWhere('funcao', 'Serviço de Pós-Graduação')['users'] as $servicoposgraduacao) {
                            $responsavel_nome = Pessoa::obterNome($servicoposgraduacao->codpes);
                            \Mail::to($servicoposgraduacao->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));
                        }

                    $request->session()->flash('alert-success', 'Sua inscrição foi enviada' . $info_adicional);
                    return view('inscricoes.index', $this->monta_compact_index());
                } else {
                    $request->session()->flash('alert-success', 'É necessário antes escolher a(s) disciplina(s)');
                    return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
                }
            } else {
                $request->session()->flash('alert-success', 'É necessário antes enviar todos os documentos exigidos');
                return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
            }
        }

        if ($request->conjunto_alterado == 'estado') {
            $this->authorize('inscricoes.updateStatus', $inscricao);

            // transaction para não ter problema de inconsistência do DB
            $inscricao = DB::transaction(function () use ($request, $inscricao) {

                $inscricao->estado = $request->estado;
                $inscricao->save();

                switch ($inscricao->estado) {
                    case 'Pré-Aprovada':
                        /*// envia e-mails avisando os orientadores da linha de pesquisa da inscrição sobre a pré-aprovação da inscrição
                        $passo = 'pré-aprovação';
                        $user = $inscricao->users()->wherePivot('papel', 'Autor')->first();
                        foreach ($inscricao->linhapesquisa->orientadores as $orientador) {
                            $orientador_nome = Orientador::obterNome($orientador->codpes);
                            \Mail::to(Orientador::obterEmail($orientador->codpes))
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'orientador_nome')));
                        }*/
                        break;

                    case 'Aprovada':
                    case 'Rejeitada':
                        // envia e-mail avisando o candidato da aprovação/rejeição da inscrição
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

            $extras = json_decode($inscricao->extras, true);
            if (isset($extras['disciplinas']))
                $request->merge(['extras' => array_merge($request->input('extras', []), ['disciplinas' => $extras['disciplinas']])]);    // pelo fato de vir do card-principal, $request->extras não vem com as disciplinas... então precisamos recuperá-las a partir de $extras
            $inscricao->extras = json_encode($request->input('extras'));
            $inscricao->save();

            $request->session()->flash('alert-success', 'Inscrição alterada com sucesso');
        }

        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit'));
    }

    /**
     * Adicionar disciplinas relacionadas à inscrição
     * autorizado a qualquer um que tenha acesso à inscrição
     * request->codpes = required, int
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
                if ($inscricao->estado != 'Aguardando Envio')
                    $info_adicional = '<br />Reenvie esta inscrição para gerar ' . ((count($extras['disciplinas']) == 1) ? 'novo boleto' : 'novos boletos');
            }

            return ['disciplina' => $disciplina, 'existia' => $existia, 'info_adicional' => $info_adicional];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'A disciplina ' . $db_transaction['disciplina']->sigla . ' - ' . $db_transaction['disciplina']->nome . ' foi adicionada à essa inscrição.' . $db_transaction['info_adicional']);
        else
            $request->session()->flash('alert-info', 'A disciplina ' . $db_transaction['disciplina']->sigla . ' - ' . $db_transaction['disciplina']->nome . ' já estava vinculada à essa inscrição.');
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', 'disciplinas'));
    }

    /**
     * Remove disciplinas relacionadas à inscrição
     * $user = required
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

        $request->session()->flash('alert-success', 'A disciplina ' . $disciplina->sigla . ' - '. $disciplina->nome . ' foi removida dessa inscrição.');
        \UspTheme::activeUrl('inscricoes');
        return view('inscricoes.edit', $this->monta_compact($inscricao, 'edit', 'disciplinas'));
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

        return compact('data', 'objetos', 'classe_nome', 'max_upload_size');
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
        $objeto->tipos_arquivo = TipoArquivo::obterTiposArquivoDaSelecao('Inscricao', ($objeto->selecao->categoria->nome == 'Aluno Especial' ? new Collection() : collect([['nome' => $nivel]])), $objeto->selecao);
        $objeto->selecao->tipos_arquivo = TipoArquivo::obterTiposArquivoPossiveis('Selecao', new Collection(), $objeto->selecao->programa_id);
        $solicitacaoisencaotaxa_aprovada = \Auth::user()?->solicitacoesIsencaoTaxa()?->where('selecao_id', $objeto->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->first();
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'responsaveis', 'inscricao_disciplinas', 'disciplinas', 'nivel', 'solicitacaoisencaotaxa_aprovada', 'max_upload_size', 'scroll');
    }
}
