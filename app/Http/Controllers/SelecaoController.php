<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelecaoRequest;
use App\Models\Selecao;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SelecaoController extends Controller
{

    // crud generico
    protected $data = [
        'title' => 'Seleções',
        'url' => 'selecoes', // caminho da rota do resource
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
     * Lista as seleções
     */
    public function index()
    {
        $this->authorize('selecoes.viewAny');
        \UspTheme::activeUrl('selecoes');

        $selecoes = Selecao::listarSelecoes();
        return view('selecoes.index')->with(['data' => (object) $this->data, 'selecoes' => $selecoes]);
    }

    /**
     * Criar nova seleção
     */
    public function store(SelecaoRequest $request)
    {
        # Para criar uma nova seleção precisamos do processo para autorizar
        $processo = Processo::find($request->processo_id);
        $this->authorize('selecoes.create', $processo);

        $selecao = Selecao::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $selecao->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);

        # aqui tem de validar dados do post
        ####################

        $selecao->fill($request->all());
        $selecao->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Selecao $selecao)
    {
        $this->authorize('selecoes.view', $selecao);
        \UspTheme::activeUrl('selecoes');

        if ($request->ajax()) {
            return $selecao;
        } else {
            $data = (object) $this->data;

            return view('selecoes.show', compact(['selecao', 'data']));
        }
    }
}
