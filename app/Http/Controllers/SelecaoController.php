<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Categoria;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Programa;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\User;
use App\Utils\JSONForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SelecaoController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Seleções',
        'url' => 'selecoes',     // caminho da rota do resource
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('selecoes.viewAny');

        \UspTheme::activeUrl('selecoes');
        $data = self::$data;
        $objetos = Selecao::listarSelecoes();
        $classe_nome = 'Selecao';
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');
        return view('selecoes.index', compact('data', 'objetos', 'classe_nome', 'max_upload_size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('selecoes.create');

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact(new Selecao, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SelecaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SelecaoRequest $request)
    {
        $this->authorize('selecoes.create');

        $validator = Validator::make($request->all(), SelecaoRequest::rules, SelecaoRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('selecoes');
            return back()->withErrors($validator)->withInput();
        }

        $requestData = $request->all();
        $requestData['datahora_inicio'] = (is_null($requestData['data_inicio'] || is_null($requestData['hora_inicio'])) ? null : Carbon::createFromFormat('d/m/Y H:i', $requestData['data_inicio'] . ' ' . $requestData['hora_inicio']));
        $requestData['datahora_fim'] = (is_null($requestData['data_fim'] || is_null($requestData['hora_fim'])) ? null : Carbon::createFromFormat('d/m/Y H:i', $requestData['data_fim'] . ' ' . $requestData['hora_fim']));
        $requestData['boleto_valor'] = str_replace(',', '.', $requestData['boleto_valor']);
        $requestData['boleto_data_vencimento'] = (is_null($requestData['boleto_data_vencimento']) ? null : Carbon::createFromFormat('d/m/Y', $requestData['boleto_data_vencimento']));
        $selecao = Selecao::create($requestData);

        $selecao->atualizarStatus();
        $selecao->estado = Selecao::where('id', $selecao->id)->value('estado');

        $request->session()->flash('alert-success', 'Seleção cadastrada com sucesso<br />' .
            'Agora adicione os documentos relacionados ao processo');

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Selecao        $selecao
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        Selecao::atualizarStatusSelecoes();

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SelecaoRequest  $request
     * @param  \App\Models\Selecao                $selecao
     * @return \Illuminate\Http\Response
     */
    public function update(SelecaoRequest $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $validator = Validator::make($request->all(), SelecaoRequest::rules, SelecaoRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('selecoes');
            return view('selecoes.edit', $this->monta_compact($selecao, 'edit'))->withErrors($validator);    // preciso especificar 'edit'... se eu fizesse um return back(), e o usuário estivesse vindo de um update após um create, a variável $modo voltaria a ser 'create', e a página ficaria errada
        }

        $this->updateField($request, $selecao, 'categoria_id', 'categoria', 'a');
        $this->updateField($request, $selecao, 'nome', 'nome', 'o');
        $this->updateField($request, $selecao, 'descricao', 'descrição', 'a');
        $this->updateField($request, $selecao, 'datahora_inicio', 'data/hora início', 'a');
        $this->updateField($request, $selecao, 'datahora_fim', 'data/hora fim', 'a');
        $this->updateField($request, $selecao, 'boleto_valor', 'valor do boleto', 'o');
        $this->updateField($request, $selecao, 'boleto_texto', 'texto do boleto', 'o');
        $this->updateField($request, $selecao, 'boleto_data_vencimento', 'data de vencimento do boleto', 'a');
        if ($selecao->programa_id != $request->programa_id && !empty($request->programa_id)) {
            if ($selecao->linhaspesquisa->count() > 0) {
                $request->session()->flash('alert-danger', 'Não se pode alterar o programa, pois há linhas de pesquisa do programa antigo cadastradas para esta seleção!');
                return back();
            }
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - Programa antigo: ' . $selecao->programa_id . ' - Novo programa: ' . $request->programa_id);
            $selecao->programa_id = $request->programa_id;
        }
        $selecao->save();

        $selecao->atualizarStatus();
        $selecao->estado = Selecao::where('id', $selecao->id)->value('estado');

        $request->session()->flash('alert-success', 'Seleção alterada com sucesso');

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    private function updateField(SelecaoRequest $request, Selecao $selecao, string $field, string $field_name, string $genero)
    {
        if ((strpos($field, 'datahora_') === 0) || (strpos($field, '_datahora_') !== false)) {
            $request->$field = (is_null($request->{str_replace('datahora_', 'data_', $field)}) ||
                                is_null($request->{str_replace('datahora_', 'hora_', $field)}) ? null : Carbon::createFromFormat('d/m/Y H:i', $request->{str_replace('datahora_', 'data_', $field)} .
                                                                                                                                              ' ' .
                                                                                                                                              $request->{str_replace('datahora_', 'hora_', $field)})->format('Y-m-d H:i'));
        }

        if ((strpos($field, 'data_') === 0) || (strpos($field, '_data_') !== false))
            $request->$field = (is_null($request->$field) ? null : Carbon::createFromFormat('d/m/Y', $request->$field)->format('Y-m-d'));

        if ($selecao->$field != $request->$field) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - ' . ucfirst($field_name) . ' antig' . $genero . ': ' . $selecao->$field . ' - Nov' . $genero . ' ' . $field_name . ': ' . $request->$field);
            $selecao->$field = $request->$field;
        }
    }

    public function storeTemplateJson(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $newjson = $request->template;
        $selecao->template = $newjson;
        $selecao->save();
        $request->session()->flash('alert-success', 'Template salvo com sucesso');
        return back();
    }

    public function createTemplate(Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);
        \UspTheme::activeUrl('selecoes');

        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        return view('selecoes.template', compact('selecao', 'template'));
    }

    public function storeTemplate(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $request->validate([
            'template.*.label' => 'required',
            'template.*.type' => 'required',
        ]);
        if (isset($request->campo)) {
            $request->validate([
                'new.label' => 'required',
                'new.type' => 'required',
            ]);
        }
        $template = [];
        // remonta $template, considerando apenas o que veio do $request (com isso, atualiza e também apaga)
        if (isset($request->template))
            foreach ($request->template as $campo => $atributos)
                $template[$campo] = array_filter($atributos, 'strlen');
        // trata campo do tipo select
        foreach ($template as $campo => $atributo)
            if (($atributo['type'] == 'select') || ($atributo['type'] == 'radio'))
                $template[$campo]['value'] = json_decode($atributo['value'], true);
        // adiciona campo novo
        $new = (!is_null($request->new)) ? array_filter($request->new, 'strlen') : null;
        if (isset($new['label']))
            $new['label'] = removeSpecialChars($new['label']);
        $new['order'] = JSONForms::getLastIndex($template, 'order') + 1;
        if (isset($request->campo)) {                           // veio do adicionar campo novo
            $request->campo = removeSpecialChars($request->campo);
            $template[$request->campo] = $new;
            if (isset($new['value']))
                $template[$request->campo]['value'] = json_decode($new['value']);    // necessário para remover " excedentes que quebravam o JSON
            elseif ($template[$request->campo]['type'] == 'select')
                $template[$request->campo]['value'] = '[]';
        }
        $selecao->template = JSONForms::fixJson($template);
        $selecao->save();
        $request->session()->flash('alert-success', 'Formulário salvo com sucesso');
        return back();
    }

    public function createTemplateValue(Selecao $selecao, string $field)
    {
        $this->authorize('selecoes.update', $selecao);
        \UspTheme::activeUrl('selecoes');

        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        return view('selecoes.templatevalue', compact('selecao', 'template', 'field'));
    }

    public function storeTemplateValue(Request $request, Selecao $selecao, string $field)
    {
        $this->authorize('selecoes.update', $selecao);

        $request->validate([
            'value.*.label' => 'required',
        ]);
        $new = (!is_null($request->new)) ? array_filter($request->new, 'strlen') : null;
        if (is_array($new) && !empty($new)) {                           // veio do adicionar campo novo
            $request->validate([
                'new.label' => 'required',
            ]);
        }
        $template = json_decode($selecao->template);
        $value = [];
        // remonta $value, considerando apenas o que veio do $request (com isso, atualiza e também apaga)
        if (isset($request->value)) {
            foreach ($request->value as $campo => $atributos) {
                $atributos['label'] = removeSpecialChars($atributos['label']);
                $atributos['value'] = substr(removeAccents(Str::of($atributos['label'])->lower()->replace([' ', '-'], '_')), 0, 32);
                $value[$campo] = array_filter($atributos, 'strlen');
            }
        }
        // adiciona campo novo
        if (is_array($new) && !empty($new)) {                           // veio do adicionar campo novo
            $new['label'] = removeSpecialChars($new['label']);
            $new['value'] = substr(removeAccents(Str::of($new['label'])->lower()->replace([' ', '-'], '_')), 0, 32);
            $new['order'] = JSONForms::getLastIndex($template->$field->value, 'order') + 1;
            $value[] = $new;
        }
        $template->$field->value = $value;
        $selecao->template = JSONForms::fixJson($template);
        $selecao->save();
        $request->session()->flash('alert-success', 'Lista salva com sucesso');
        return back();
    }

    /**
     * Adicionar linhas de pesquisa relacionadas à seleção
     * autorizado a qualquer um que tenha acesso à seleção
     * request->codpes = required, int
     */
    public function storeLinhaPesquisa(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Linha de pesquisa obrigatória',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $selecao) {

            $linhapesquisa = LinhaPesquisa::where('id', $request->id)->first();

            $existia = $selecao->linhaspesquisa()->detach($linhapesquisa);

            $selecao->linhaspesquisa()->attach($linhapesquisa);

            return ['linhapesquisa' => $linhapesquisa, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'A linha de pesquisa ' . $db_transaction['linhapesquisa']->nome . ' foi adicionada à essa seleção.');
        else
            $request->session()->flash('alert-info', 'A linha de pesquisa ' . $db_transaction['linhapesquisa']->nome . ' já estava vinculada à essa seleção.');
        return back();
    }

    /**
     * Remove linhas de pesquisa relacionadas à seleção
     * $user = required
     */
    public function destroyLinhaPesquisa(Request $request, Selecao $selecao, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('selecoes.update', $selecao);

        $selecao->linhaspesquisa()->detach($linhapesquisa);

        $request->session()->flash('alert-success', 'A linha de pesquisa ' . $linhapesquisa->nome . ' foi removida dessa seleção.');
        return back();
    }

    /**
     * Adicionar motivos de isenção de taxa relacionados à seleção
     * autorizado a qualquer um que tenha acesso à seleção
     * request->codpes = required, int
     */
    public function storeMotivoIsencaoTaxa(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update', $selecao);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Motivo de isenção de taxa obrigatório',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $selecao) {

            $motivoisencaotaxa = MotivoIsencaoTaxa::where('id', $request->id)->first();

            $existia = $selecao->motivosisencaotaxa()->detach($motivoisencaotaxa);

            $selecao->motivosisencaotaxa()->attach($motivoisencaotaxa);

            return ['motivoisencaotaxa' => $motivoisencaotaxa, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'O motivo de isenção de taxa ' . $db_transaction['motivoisencaotaxa']->nome . ' foi adicionado à essa seleção');
        else
            $request->session()->flash('alert-info', 'O motivo de isenção de taxa ' . $db_transaction['motivoisencaotaxa']->nome . ' já estava vinculado à essa seleção');
        return back();
    }

    /**
     * Remove motivos de isenção de taxa relacionados à seleção
     * $user = required
     */
    public function destroyMotivoIsencaoTaxa(Request $request, Selecao $selecao, MotivoIsencaoTaxa $motivoisencaotaxa)
    {
        $this->authorize('selecoes.update', $selecao);

        $selecao->motivosisencaotaxa()->detach($motivoisencaotaxa);

        $request->session()->flash('alert-success', 'O motivo de isenção de taxa ' . $motivoisencaotaxa->nome . ' foi removido dessa seleção');
        return back();
    }

    /**
     * Baixa as solicitações de isenção de taxa especificadas
     *
     * @param $request->ano
     * @param $selecao
     * @return Stream
     */
    public function downloadSolicitacoesIsencaoTaxa(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);
        $request->validate([
            'ano' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);
        $ano = $request->ano;

        $solicitacoesisencaotaxa = SolicitacaoIsencaoTaxa::listarSolicitacoesIsencaoTaxaPorSelecao($selecao, $ano);

        // vamos pegar o template da seleção para saber quais são os campos extras
        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        $keys = array_keys($template);

        $arr = [];
        foreach ($solicitacoesisencaotaxa as $solicitacaoisencaotaxa) {
            $i = [];

            $i['programa'] = $solicitacaoisencaotaxa->selecao->programa->nome;
            $i['selecao'] = $solicitacaoisencaotaxa->selecao->nome;

            $autor = $solicitacaoisencaotaxa->users()->wherePivot('papel', 'Autor')->first();
            $i['autor'] = $autor ? $autor->name : '';

            $extras = json_decode($solicitacaoisencaotaxa->extras, true) ?? [];
            foreach ($keys as $field)
                if (in_array($field, ['nome', 'tipo_de_documento', 'numero_do_documento', 'cpf', 'e_mail']))    // somente estes campos do formulário da seleção são utilizados na solicitação de isenção de taxa
                    $i[$field] = isset($extras[$field]) ? $extras[$field] : '';

            $i['criado_em'] = $solicitacaoisencaotaxa->created_at->format('d/m/Y');
            $i['atualizado_em'] = $solicitacaoisencaotaxa->updated_at->format('d/m/Y');

            $arr[] = $i;
        }

        $writer = SimpleExcelWriter::streamDownload('solicitacoesisencaotaxa_' . $ano . '_selecao' . $selecao->id . '.xlsx')
            ->addRows($arr);
    }

    /**
     * Baixa as inscrições especificadas
     *
     * @param $request->ano
     * @param $selecao
     * @return Stream
     */
    public function downloadInscricoes(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);
        $request->validate([
            'ano' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);
        $ano = $request->ano;

        $inscricoes = Inscricao::listarInscricoesPorSelecao($selecao, $ano);

        // vamos pegar o template da seleção para saber quais são os campos extras
        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        $keys = array_keys($template);

        $arr = [];
        foreach ($inscricoes as $inscricao) {
            $i = [];

            $i['programa'] = $inscricao->selecao->programa->nome;
            $i['selecao'] = $inscricao->selecao->nome;
            $i['linhapesquisa'] = $inscricao->linhapesquisa->nome;

            $autor = $inscricao->users()->wherePivot('papel', 'Autor')->first();
            $i['autor'] = $autor ? $autor->name : '';

            $extras = json_decode($inscricao->extras, true) ?? [];
            foreach ($keys as $field)
                $i[$field] = isset($extras[$field]) ? $extras[$field] : '';

            $i['criado_em'] = $inscricao->created_at->format('d/m/Y');
            $i['atualizado_em'] = $inscricao->updated_at->format('d/m/Y');

            $arr[] = $i;
        }

        $writer = SimpleExcelWriter::streamDownload('inscricoes_' . $ano . '_selecao' . $selecao->id . '.xlsx')
            ->addRows($arr);
    }

    private function monta_compact(Selecao $selecao, string $modo)
    {
        $data = (object) self::$data;
        $selecao->template = JSONForms::orderTemplate($selecao->template);
        $objeto = $selecao;
        $classe_nome = 'Selecao';
        $classe_nome_plural = 'selecoes';
        $rules = SelecaoRequest::rules;
        $linhaspesquisa = LinhaPesquisa::listarLinhasPesquisa(is_null($objeto->programa) ? (new Programa) : $objeto->programa);
        $motivosisencaotaxa = MotivoIsencaoTaxa::listarMotivosIsencaoTaxa();
        $max_upload_size = config('inscricoes-selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'modo', 'linhaspesquisa', 'motivosisencaotaxa', 'max_upload_size', 'rules');
    }
}
