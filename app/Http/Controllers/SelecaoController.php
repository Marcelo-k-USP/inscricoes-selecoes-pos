<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Categoria;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\Programa;
use App\Models\Selecao;
use App\Utils\JSONForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
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
        $requestData['data_inicio'] = (is_null($requestData['data_inicio']) ? null : Carbon::createFromFormat('d/m/Y', $requestData['data_inicio']));
        $requestData['data_fim'] = (is_null($requestData['data_fim']) ? null : Carbon::createFromFormat('d/m/Y', $requestData['data_fim']));
        $requestData['boleto_valor'] = str_replace(',', '.', $requestData['boleto_valor']);
        $requestData['boleto_data_vencimento'] = (is_null($requestData['boleto_data_vencimento']) ? null : Carbon::createFromFormat('d/m/Y', $requestData['boleto_data_vencimento']));
        $selecao = Selecao::create($requestData);

        Selecao::atualizaStatusSelecoes();
        $selecao->estado = Selecao::where('id', $selecao->id)->value('estado');

        $request->session()->flash('alert-success', 'Seleção cadastrada com sucesso<br />' .
            'Agora adicione o edital e demais arquivos relacionados ao processo');

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
        $this->authorize('selecoes.update');

        Selecao::atualizaStatusSelecoes();

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
        $this->authorize('selecoes.update');

        $validator = Validator::make($request->all(), SelecaoRequest::rules, SelecaoRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('selecoes');
            return view('selecoes.edit', $this->monta_compact($selecao, 'edit'))->withErrors($validator);    // preciso especificar 'edit'... se eu fizesse um return back(), e o usuário estivesse vindo de um update após um create, a variável $modo voltaria a ser 'create', e a página ficaria errada
        }

        $this->updateField($request, $selecao, 'categoria_id', 'categoria', 'a');
        $this->updateField($request, $selecao, 'nome', 'nome', 'o');
        $this->updateField($request, $selecao, 'descricao', 'descrição', 'a');
        $this->updateField($request, $selecao, 'data_inicio', 'data início', 'a');
        $this->updateField($request, $selecao, 'data_fim', 'data fim', 'a');
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

        Selecao::atualizaStatusSelecoes();
        $selecao->estado = Selecao::where('id', $selecao->id)->value('estado');

        $request->session()->flash('alert-success', 'Seleção alterada com sucesso');

        \UspTheme::activeUrl('selecoes');
        return view('selecoes.edit', $this->monta_compact($selecao, 'edit'));
    }

    private function updateField(SelecaoRequest $request, Selecao $selecao, string $field, string $field_name, string $genero)
    {
        if ((strpos($field, 'data_') === 0) || (strpos($field, '_data_') !== false))
            $request->$field = (is_null($request->$field) ? null : Carbon::createFromFormat('d/m/Y', $request->$field)->format('Y-m-d'));

        if ($selecao->$field != $request->$field) {
            Log::info(' - Edição de seleção - Usuário: ' . \Auth::user()->codpes . ' - ' . \Auth::user()->name . ' - Id Seleção: ' . $selecao->id . ' - ' . ucfirst($field_name) . ' antig' . $genero . ': ' . $selecao->$field . ' - Nov' . $genero . ' ' . $field_name . ': ' . $request->$field);
            $selecao->$field = $request->$field;
        }
    }

    public function storeTemplateJson(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update');

        $newjson = $request->template;
        $selecao->template = $newjson;
        $selecao->save();
        $request->session()->flash('alert-success', 'Template salvo com sucesso');
        return back();
    }

    public function createTemplate(Selecao $selecao)
    {
        $this->authorize('selecoes.update');
        \UspTheme::activeUrl('selecoes');

        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        return view('selecoes.template', compact('selecao', 'template'));
    }

    public function storeTemplate(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.update');

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
        $this->authorize('selecoes.update');
        \UspTheme::activeUrl('selecoes');

        $template = json_decode(JSONForms::orderTemplate($selecao->template), true);
        return view('selecoes.templatevalue', compact('selecao', 'template', 'field'));
    }

    public function storeTemplateValue(Request $request, Selecao $selecao, string $field)
    {
        $this->authorize('selecoes.update');

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
        $this->authorize('selecoes.update');

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Linha de pesquisa obrigatória',
        ]);

        $linhapesquisa = LinhaPesquisa::where('id', $request->id)->first();

        $existia = $selecao->linhaspesquisa()->detach($linhapesquisa);

        $selecao->linhaspesquisa()->attach($linhapesquisa);

        if (!$existia)
            $request->session()->flash('alert-success', 'A linha de pesquisa ' . $linhapesquisa->nome . ' foi adicionada à essa seleção.');
        else
            $request->session()->flash('alert-info', 'A linha de pesquisa ' . $linhapesquisa->nome . ' já estava vinculada à essa seleção.');
        return back();
    }

    /**
     * Remove linhas de pesquisa relacionadas à seleção
     * $user = required
     */
    public function destroyLinhaPesquisa(Request $request, Selecao $selecao, LinhaPesquisa $linhapesquisa)
    {
        $this->authorize('selecoes.update');

        $selecao->linhaspesquisa()->detach($linhapesquisa);

        $request->session()->flash('alert-success', 'A linha de pesquisa ' . $linhapesquisa->nome . ' foi removida dessa seleção.');
        return back();
    }

    /**
     * Baixa as inscrições especificadas
     *
     * @param $request->ano
     * @param $selecao
     * @return Stream
     */
    public function download(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view');
        $request->validate([
            'ano' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);
        $ano = $request->ano;

        $inscricoes = Inscricao::listarInscricoesPorSelecao($selecao, $ano);

        // vamos pegar o template da seleção para saber quais são os campos extras
        $template = array_keys(json_decode($selecao->template, true));

        $arr = [];
        foreach ($inscricoes as $inscricao) {
            $i = [];

            $autor = $inscricao->users()->wherePivot('papel', 'Autor')->first();
            $i['autor'] = $autor ? $autor->name : '';

            $i['extras'] = $inscricao->extras;
            $extras = json_decode($inscricao->extras, true) ?? [];
            foreach ($template as $field) {
                $i['extra_' . $field] = isset($extras[$field]) ? $extras[$field] : '';
            }

            $i['criado_em'] = $inscricao->created_at->format('d/m/Y');
            $i['atualizado_em'] = $inscricao->updated_at->format('d/m/Y');

            $arr[] = $i;
        }

        $writer = SimpleExcelWriter::streamDownload('inscricoes_' . $ano . '_selecao' . $selecao->id . '.xlsx')
            ->addRows($arr);
    }

    private function monta_compact(Selecao $selecao, string $modo) {
        $data = (object) self::$data;
        $selecao->template = JSONForms::orderTemplate($selecao->template);
        $objeto = $selecao;
        $classe_nome = 'Selecao';
        $classe_nome_plural = 'selecoes';
        $rules = SelecaoRequest::rules;
        $linhaspesquisa = LinhaPesquisa::listarLinhasPesquisa(is_null($objeto->programa) ? (new Programa) : $objeto->programa);
        $max_upload_size = config('selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'modo', 'linhaspesquisa', 'max_upload_size', 'rules');
    }
}
