<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoArquivoRequest;
use App\Models\Nivel;
use App\Models\TipoArquivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class TipoArquivoController extends Controller
{
    // crud generico
    public static $data = [
        'title' => 'Tipos de Documento',
        'url' => 'tiposarquivo',     // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\TipoArquivo',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('tiposarquivo.viewAny');

        \UspTheme::activeUrl('tiposarquivo');
        if (!$request->ajax())
            return view('tiposarquivo.tree', $this->monta_compact_index());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('tiposarquivo.create');

        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact(new TipoArquivo, 'create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TipoArquivoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoArquivoRequest $request)
    {
        $this->authorize('tiposarquivo.create');

        $validator = Validator::make($request->all(), TipoArquivoRequest::rules, TipoArquivoRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('tiposarquivo');
            return back()->withErrors($validator)->withInput();
        }

        $request->merge(['obrigatorio' => $request->has('obrigatorio')]);    // acerta o valor do campo "obrigatorio" (pois, se o usuário deixou false, o campo não vem no $request e, se o usuário deixou true, ele vem mas com valor null)

        // transaction para não ter problema de inconsistência do DB
        $tipoarquivo = DB::transaction(function () use ($request) {

            $tipoarquivo = TipoArquivo::create($request->all());

            if ($tipoarquivo->classe_nome == 'Inscrições')
                foreach (Nivel::all() as $nivel)    // cadastra automaticamente todos os níveis como possíveis para este tipo de arquivo
                    $tipoarquivo->niveis()->attach($nivel->id);

            return $tipoarquivo;
        });

        $request->session()->flash('alert-success', 'Tipo de documento cadastrado com sucesso');
        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact($tipoarquivo, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\TipoArquivo  $tipoarquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TipoArquivo $tipoarquivo)
    {
        $this->authorize('tiposarquivo.update');

        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact($tipoarquivo, 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TipoArquivoRequest  $request
     * @param  \App\Models\TipoArquivo                $tipoarquivo
     * @return \Illuminate\Http\Response
     */
    public function update(TipoArquivoRequest $request, TipoArquivo $tipoarquivo)
    {
        $this->authorize('tiposarquivo.update');

        $validator = Validator::make($request->all(), TipoArquivoRequest::rules, TipoArquivoRequest::messages);
        if ($validator->fails()) {
            \UspTheme::activeUrl('tiposarquivo');
            return back()->withErrors($validator)->withInput();
        }

        $request->merge(['obrigatorio' => $request->has('obrigatorio')]);    // acerta o valor do campo "obrigatorio" (pois, se o usuário deixou false, o campo não vem no $request e, se o usuário deixou true, ele vem mas com valor null)

        $tipoarquivo->nome = $request->nome;
        $tipoarquivo->obrigatorio = $request->obrigatorio;
        $tipoarquivo->minimo = $request->minimo;
        $tipoarquivo->save();

        $request->session()->flash('alert-success', 'Tipo de documento alterado com sucesso');
        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact($tipoarquivo, 'edit'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\TipoArquivoRequest  $request
     * @param  string                                 $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoArquivoRequest $request, string $id)
    {
        $this->authorize('tiposarquivo.delete');

        $tipoarquivo = TipoArquivo::find((int) $id);
        if ($tipoarquivo->selecoes()->exists())
            $request->session()->flash('alert-danger', 'Há seleções que usam este tipo de documento!');
        elseif ($tipoarquivo->arquivos()->exists())
            $request->session()->flash('alert-danger', 'Há arquivos armazenados deste tipo!');
        else {
            $tipoarquivo->delete();
            $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        }
        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.tree', $this->monta_compact_index());
    }

    /**
     * Adicionar níveis relacionados ao tipo de arquivo
     * autorizado a qualquer um que tenha acesso ao tipo de arquivo
     * request->codpes = required, int
     */
    public function storeNivel(Request $request, TipoArquivo $tipoarquivo)
    {
        $this->authorize('tiposarquivo.update', $tipoarquivo);

        $request->validate([
            'id' => 'required',
        ],
        [
            'id.required' => 'Nível obrigatório',
        ]);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($request, $tipoarquivo) {

            $nivel = Nivel::where('id', $request->id)->first();

            $existia = $tipoarquivo->niveis()->detach($nivel);

            $tipoarquivo->niveis()->attach($nivel);

            return ['nivel' => $nivel, 'existia' => $existia];
        });

        if (!$db_transaction['existia'])
            $request->session()->flash('alert-success', 'O nível ' . $db_transaction['nivel']->nome . ' foi adicionado a esse tipo de documento');
        else
            $request->session()->flash('alert-info', 'O nível ' . $db_transaction['nivel']->nome . ' já estava vinculado a esse tipo de documento');
        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact($tipoarquivo, 'edit'));
    }

    /**
     * Remove níveis relacionados ao tipo de arquivo
     * $user = required
     */
    public function destroyNivel(Request $request, TipoArquivo $tipoarquivo, Nivel $nivel)
    {
        $this->authorize('tiposarquivo.update', $tipoarquivo);

        $tipoarquivo->niveis()->detach($nivel);

        $request->session()->flash('alert-success', 'O nível ' . $nivel->nome . ' foi removido desse tipo de documento');
        \UspTheme::activeUrl('tiposarquivo');
        return view('tiposarquivo.edit', $this->monta_compact($tipoarquivo, 'edit'));
    }

    private function monta_compact_index()
    {
        $tiposarquivo = TipoArquivo::orderByRaw("
            CASE
                WHEN classe_nome = 'Seleções'                        THEN 1
                WHEN classe_nome = 'Solicitações de Isenção de Taxa' THEN 2
                WHEN classe_nome = 'Inscrições'                      THEN 3
                ELSE 4
            END
        ")->orderBy('id')->get();
        $fields = TipoArquivo::getFields();
        $modal['url'] = 'tiposarquivo';
        $modal['title'] = 'Editar Tipo de Documento';
        $rules = TipoArquivoRequest::rules;

        return compact('tiposarquivo', 'fields', 'modal', 'rules');
    }

    private function monta_compact(TipoArquivo $tipoarquivo, string $modo)
    {
        $data = (object) self::$data;
        $objeto = $tipoarquivo;
        $niveis = Nivel::all();
        $rules = TipoArquivoRequest::rules;

        return compact('data', 'objeto', 'niveis', 'rules', 'modo');
    }
}
