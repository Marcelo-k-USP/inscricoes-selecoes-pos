<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessoRequest;
use App\Models\Processo;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ProcessoController extends Controller
{

    // crud generico
    protected $data = [
        'title' => 'Processos',
        'url' => 'processos', // caminho da rota do resource
        'modal' => true,
        'showId' => false,
        'viewBtn' => true,
        'editBtn' => false,
        'model' => 'App\Models\Processo',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista os processos
     */
    public function index()
    {
        $this->authorize('processos.viewAny');
        \UspTheme::activeUrl('processos');

        $processos = Processo::listarProcessos();
        return view('processos.index')->with(['data' => (object) $this->data, 'processos' => $processos]);
    }

    /**
     * Criar novo processo
     */
    public function store(ProcessoRequest $request)
    {
        $this->authorize('processos.create');

        $processo = Processo::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return redirect('/' . $this->data['url'] . '/' . $processo->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Processo $processo)
    {
        $this->authorize('processos.view', $processo);

        # aqui tem de validar dados do post
        ####################

        $processo->fill($request->all());

        $processo->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Processo $processo)
    {
        $this->authorize('processos.view', $processo);
        \UspTheme::activeUrl('processos');

        if ($request->ajax()) {
            return $processo;
        } else {
            $data = (object) $this->data;
            $dashboard = new \StdClass;

            return view('processos.show', compact(['processo', 'data', 'dashboard']));
        }
    }
}
