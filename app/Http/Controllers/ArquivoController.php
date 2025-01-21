<?php

namespace App\Http\Controllers;

use App\Mail\InscricaoMail;
use App\Mail\SolicitacaoIsencaoTaxaMail;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Programa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Services\BoletoService;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Uspdev\Replicado\Pessoa;

class ArquivoController extends Controller
{
    protected $boletoService;

    public function __construct(BoletoService $boletoService)
    {
        $this->middleware('auth')->except('show');
        $this->boletoService = $boletoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        if (Arquivo::find($arquivo->id)->selecoes()->exists())
            $classe_nome = 'Selecao';
        elseif (Arquivo::find($arquivo->id)->inscricoes()->exists() && in_array(Arquivo::find($arquivo->id)->inscricoes()->first()->estado, (new SolicitacaoIsencaoTaxa())->estados()))
            $classe_nome = 'SolicitacaoIsencaoTaxa';
        else
            $classe_nome = 'Inscricao';
        $this->authorize('arquivos.view', [$arquivo, $classe_nome]);

        ob_end_clean();    // https://stackoverflow.com/questions/39329299/laravel-file-downloaded-from-storage-folder-gets-corrupted

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:' . config('inscricoes-selecoes-pos.upload_max_filesize'),
            'objeto_id' => 'required|integer|exists:' . $classe_nome_plural . ',id',
        ]);
        $this->authorize('arquivos.create', [$objeto, $classe_nome]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $classe_nome, $classe_nome_plural, $objeto) {

            foreach ($request->arquivo as $arq) {
                $arquivo = new Arquivo;
                $arquivo->user_id = \Auth::user()->id;
                $arquivo->nome_original = $arq->getClientOriginalName();
                $arquivo->caminho = $arq->store('./arquivos/' . $objeto->created_at->year);
                $arquivo->mimeType = $arq->getClientMimeType();
                $arquivo->save();

                $arquivo->{$classe_nome_plural}()->attach($objeto->id, ['tipo' => $request->tipo_arquivo]);
            }

            $info_adicional = '';
            switch ($classe_nome) {
                case 'Selecao':
                    $objeto->atualizarStatus();
                    $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');
                    break;

                case 'SolicitacaoIsencaoTaxa':
                    $objeto->verificarArquivos();
                    if (($objeto->estado == 'Isenção de Taxa Solicitada')) {

                        $solicitacaoisencaotaxa = $objeto;
                        $user = \Auth::user();

                        // envia e-mails avisando o serviço de pós-graduação sobre a solicitação da isenção de taxa
                        $passo = 'realização';
                        foreach (collect((new Programa)->obterResponsaveis())->firstWhere('funcao', 'Serviço de Pós-Graduação')['users'] as $servicoposgraduacao) {
                            $servicoposgraduacao_nome = Pessoa::obterNome($servicoposgraduacao->codpes);
                            \Mail::to($servicoposgraduacao->email)
                                ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user', 'servicoposgraduacao_nome')));
                        }

                        $info_adicional = '<br />' .
                            'Sua solicitação de isenção de taxa de inscrição foi completada';
                    }
                    break;

                case 'Inscricao':
                    $objeto->verificarArquivos();
                    if (($objeto->estado == 'Realizada') && (!$objeto->boleto_enviado)) {

                        $inscricao = $objeto;
                        $user = \Auth::user();
                        if (!$user->solicitacoesIsencaoTaxa()->where('selecao_id', $objeto->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->exists()) {

                            // envia e-mail para o candidato com o boleto
                            $passo = 'boleto';
                            $papel = 'Candidato';
                            $arquivo_nome = 'boleto.pdf';
                            $arquivo_conteudo = $this->boletoService->gerarBoleto($inscricao);
                            \Mail::to($user->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'papel', 'arquivo_nome', 'arquivo_conteudo')));

                            $objeto->load('arquivos');         // atualiza a relação de arquivos da inscrição, pois foi gerado mais um arquivo (boleto) para ela
                            $objeto->boleto_enviado = true;    // marca a inscrição como com boleto enviado
                            $objeto->save();

                            $info_adicional = '<br />' .
                                'Sua inscrição foi completada e seu boleto foi enviado, não deixe de pagá-lo';
                        } else
                            $info_adicional = '<br />' .
                                'Sua inscrição foi completada';

                        // envia e-mails avisando os secretários do programa da seleção da inscrição sobre a realização da inscrição
                        $passo = 'realização';
                        foreach (collect($inscricao->selecao->programa->obterResponsaveis())->firstWhere('funcao', 'Secretários(as) do Programa')['users'] as $secretario) {
                            $secretario_nome = Pessoa::obterNome($secretario->codpes);
                            \Mail::to($secretario->email)
                                ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'secretario_nome')));
                        }

                    }
            }

            return ['objeto' => $objeto, 'info_adicional' => $info_adicional];
        });
        $objeto = $db_transaction['objeto'];

        $request->session()->flash('alert-success', 'Documento(s) adicionado(s) com sucesso' . $db_transaction['info_adicional']);

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $request->validate(
            ['nome_arquivo' => 'required'],
            ['nome_arquivo.required' => 'O nome do arquivo é obrigatório!']
        );
        $this->authorize('arquivos.update', [$arquivo, $objeto, $classe_nome]);

        $nome_antigo = $arquivo->nome_original;
        $extensao = pathinfo($nome_antigo, PATHINFO_EXTENSION);
        $arquivo->nome_original = $request->nome_arquivo . '.' . $extensao;
        $arquivo->update();

        $request->session()->flash('alert-success', 'Documento renomeado com sucesso');

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $this->authorize('arquivos.delete', [$arquivo, $objeto, $classe_nome]);

        if (Storage::exists($arquivo->caminho))
            Storage::delete($arquivo->caminho);

        // transaction para não ter problema de inconsistência do DB
        $objeto = DB::transaction(function () use ($request, $arquivo, $classe_nome, $classe_nome_plural, $objeto) {

            $arquivo->{$classe_nome_plural}()->detach($objeto->id, ['tipo' => $request->tipo_arquivo]);
            $arquivo->delete();

            switch ($classe_nome) {
                case 'Selecao':
                    $objeto->atualizarStatus();
                    $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');
                    break;
                case 'SolicitacaoIsencaoTaxa':
                case 'Inscricao':
                    // ambos 'SolicitacaoIsencaoTaxa' e 'Inscricao' executam a linha abaixo
                    $objeto->verificarArquivos();
            }

            return $objeto;
        });

        $request->session()->flash('alert-success', 'Documento removido com sucesso');

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    private function obterClasseNomePlural(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'selecoes';
            case 'SolicitacaoIsencaoTaxa':
                return 'solicitacoesisencaotaxa';
            case 'Inscricao':
                return 'inscricoes';
        }
    }

    private function obterClasse(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return Selecao::class;
            case 'SolicitacaoIsencaoTaxa':
                return SolicitacaoIsencaoTaxa::class;
            case 'Inscricao':
                return Inscricao::class;
        }
    }

    private function obterForm(string $classe_nome, object $objeto) {
        switch ($classe_nome) {
            case 'Selecao':
                return null;
            case 'SolicitacaoIsencaoTaxa':
            case 'Inscricao':
                // ambos 'SolicitacaoIsencaoTaxa' e 'Inscricao' executam as linhas abaixo
                $objeto->selecao->template = JSONForms::orderTemplate($objeto->selecao->template);
                return JSONForms::generateForm($objeto->selecao, $classe_nome, $objeto);
        }
    }

    private function monta_compact(object $objeto, string $classe_nome, string $classe_nome_plural, $form, string $modo)
    {
        $data = (object) ('App\\Http\\Controllers\\' . $classe_nome . 'Controller')::$data;
        $linhaspesquisa = LinhaPesquisa::all();
        $motivosisencaotaxa = MotivoIsencaoTaxa::listarMotivosIsencaoTaxa();
        $responsaveis = (($classe_nome == 'Selecao') ? $objeto : $objeto->selecao)->programa->obterResponsaveis();
        $solicitacaoisencaotaxa_aprovada = \Auth::user()->solicitacoesIsencaoTaxa()->where('selecao_id', ($classe_nome == 'Inscricao') ? $objeto->selecao_id : 0)->where('estado', 'Isenção de Taxa Aprovada')->first();
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'linhaspesquisa', 'motivosisencaotaxa', 'responsaveis', 'solicitacaoisencaotaxa_aprovada', 'max_upload_size');
    }
}
