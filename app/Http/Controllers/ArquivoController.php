<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Disciplina;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Nivel;
use App\Models\NivelLinhaPesquisa;
use App\Models\Programa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\TipoArquivo;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArquivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe_nome_plural_acentuado = $this->obterClasseNomePluralAcentuado($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $validator = \Validator::make($request->all(), [
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:' . config('inscricoes-selecoes-pos.upload_max_filesize'),
            'objeto_id' => 'required|integer|exists:' . $classe_nome_plural . ',id',
        ]);
        if ($validator->fails()) {
            \UspTheme::activeUrl($classe_nome_plural);
            return view($classe_nome_plural . '.edit', array_merge($this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'), ['errors' => $validator->errors()]));
        }
        $this->authorize('arquivos.create', [$objeto, $classe_nome]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $classe_nome, $classe_nome_plural, $classe_nome_plural_acentuado, $objeto) {

            foreach ($request->arquivo as $arq) {
                $arquivo = new Arquivo;
                $arquivo->user_id = \Auth::user()->id;
                $arquivo->nome_original = $arq->getClientOriginalName();
                $arquivo->caminho = $arq->store('./arquivos/' . $objeto->created_at->year);
                $arquivo->mimeType = $arq->getClientMimeType();
                $arquivo->tipoarquivo_id = TipoArquivo::where('classe_nome', $classe_nome_plural_acentuado)->where('nome', $request->tipoarquivo)->first()->id;
                $arquivo->save();

                $arquivo->{$classe_nome_plural}()->attach($objeto->id, ['tipo' => $request->tipoarquivo]);
            }

            if ($classe_nome == 'Selecao') {
                $objeto->atualizarStatus();
                $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');

                $request->session()->flash('alert-success', 'Documento(s) adicionado(s) com sucesso<br />');
            } else {
                $classe_nome_formatada = $this->obterClasseNomeFormatada($classe_nome);
                $request->session()->flash('alert-success', 'Documento(s) adicionado(s) com sucesso<br />' .
                    'Se não houver mais arquivos a enviar, clique no botão "Enviar ' . ($classe_nome === 'SolicitacaoIsencaoTaxa' ? 'Solicitação' : 'Inscrição') . '" abaixo para efetivar sua ' . $classe_nome_formatada . '<br />' .
                    'Sem isso, sua ' . $classe_nome_formatada . ' não será ' . ($classe_nome === 'SolicitacaoIsencaoTaxa' ? 'avaliada' : 'efetivada') . '!');
            }

            return $objeto;
        });

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit', 'arquivos'));
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
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit', 'arquivos'));
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

            $arquivo->{$classe_nome_plural}()->detach($objeto->id, ['tipo' => $request->tipoarquivo]);
            $arquivo->delete();

            if ($classe_nome == 'Selecao') {
                $objeto->atualizarStatus();
                $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');
            }

            return $objeto;
        });

        $request->session()->flash('alert-success', 'Documento removido com sucesso');
        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit', 'arquivos'));
    }

    private function obterClasseNomeFormatada(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'seleção';
            case 'SolicitacaoIsencaoTaxa':
                return 'solicitação de isenção de taxa';
            case 'Inscricao':
                return 'inscrição';
        }
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

    private function obterClasseNomePluralAcentuado(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'Seleções';
            case 'SolicitacaoIsencaoTaxa':
                return 'Solicitações de Isenção de Taxa';
            case 'Inscricao':
                return 'Inscrições';
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

    private function monta_compact(object $objeto, string $classe_nome, string $classe_nome_plural, $form, string $modo, ?string $scroll = null)
    {
        $data = (object) ('App\\Http\\Controllers\\' . $classe_nome . 'Controller')::$data;
        $selecao = ($classe_nome == 'Selecao' ? $objeto : $objeto->selecao);
        $disciplinas = Disciplina::all();
        $motivosisencaotaxa = MotivoIsencaoTaxa::listarMotivosIsencaoTaxa();
        $responsaveis = $selecao->programa?->obterResponsaveis() ?? (new Programa())->obterResponsaveis();
        $extras = json_decode($objeto->extras, true);
        $objeto->niveislinhaspesquisa = NivelLinhaPesquisa::obterNiveisLinhasPesquisaDaSelecao($selecao);
        $niveislinhaspesquisa = NivelLinhaPesquisa::obterNiveisLinhasPesquisaPossiveis($selecao->programa_id);
        $inscricao_disciplinas = ((isset($extras['disciplinas']) && is_array($extras['disciplinas'])) ? Disciplina::whereIn('id', $extras['disciplinas'])->get() : collect());
        $nivel = (isset($extras['nivel']) ? Nivel::where('id', $extras['nivel'])->first()->nome : '');
        $solicitacaoisencaotaxa_aprovada = \Auth::user()->solicitacoesIsencaoTaxa()->where('selecao_id', ($classe_nome == 'Inscricao') ? $objeto->selecao_id : 0)->where('estado', 'Isenção de Taxa Aprovada')->first();
        $objeto->tiposarquivo = TipoArquivo::obterTiposArquivoDaSelecao($classe_nome, ($selecao->categoria->nome == 'Aluno Especial' ? new Collection() : (!empty($nivel) ? collect([['nome' => $nivel]]) : Nivel::all())), $selecao);
        $tiposarquivo_selecao = TipoArquivo::obterTiposArquivoPossiveis('Selecao', null, $selecao->programa_id);
        if ($classe_nome == 'Selecao') {
            $objeto->disciplinas = $objeto->disciplinas->sortBy('sigla');
            $objeto->tiposarquivo = TipoArquivo::obterTiposArquivoPossiveis('Selecao', null, $selecao->programa_id)
                                ->filter(function ($tipoarquivo) use ($selecao) { return ($tipoarquivo->nome !== 'Normas para Isenção de Taxa') || $selecao->tem_taxa; })
                            ->merge(TipoArquivo::obterTiposArquivoDaSelecao('SolicitacaoIsencaoTaxa', null, $selecao))
                            ->merge(TipoArquivo::obterTiposArquivoDaSelecao('Inscricao', ($selecao->categoria?->nome == 'Aluno Especial' ? new Collection() : (!empty($nivel) ? collect([['nome' => $nivel]]) : Nivel::all())), $selecao)
                                ->filter(function ($tipoarquivo) { return $tipoarquivo->nome !== 'Boleto(s) de Pagamento da Inscrição'; }))
                                ->sortBy(function ($tipoarquivo) { return $tipoarquivo->nome === 'Boleto(s) de Pagamento da Inscrição' ? 1 : 0; });
        } elseif ($classe_nome == 'Inscricao') {
            $objeto->tiposarquivo = $objeto->tiposarquivo->filter(function ($tipoarquivo) use ($selecao) { return ($tipoarquivo->nome !== 'Boleto(s) de Pagamento da Inscrição') || $selecao->tem_taxa; })
                                                         ->sortBy(function ($tipoarquivo) { return $tipoarquivo->nome === 'Boleto(s) de Pagamento da Inscrição' ? 1 : 0; });
            $tiposarquivo_selecao = $tiposarquivo_selecao->filter(function ($tipoarquivo) use ($selecao) { return ($tipoarquivo->nome !== 'Normas para Isenção de Taxa') || $selecao->tem_taxa; });
        }
        $tiposarquivo_solicitacaoisencaotaxa = TipoArquivo::obterTiposArquivoPossiveis('SolicitacaoIsencaoTaxa', null, $selecao->programa_id);
        $tiposarquivo_inscricao = TipoArquivo::obterTiposArquivoPossiveis('Inscricao', ($selecao->categoria->nome == 'Aluno Especial' ? new Collection() : Nivel::all()), $selecao->programa_id);
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'disciplinas', 'motivosisencaotaxa', 'responsaveis', 'niveislinhaspesquisa', 'inscricao_disciplinas', 'nivel', 'solicitacaoisencaotaxa_aprovada', 'tiposarquivo_selecao', 'tiposarquivo_solicitacaoisencaotaxa', 'tiposarquivo_inscricao', 'max_upload_size', 'scroll');
    }
}
