<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ProcessoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra lista de processos
     */
    public function index(Request $request)
    {
        $this->authorize('processos.viewAny');
        \UspTheme::activeUrl('processos');
        
        $processos = Processo::all();
        $fields = Processo::getFields();

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $processos]);
        } else {
            $modal['url'] = 'processos';
            $modal['title'] = 'Editar processo';
            return view('processos.tree', compact('processos', 'fields', 'modal'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        #usando no ajax, somente para admin
        $this->authorize('admin');
        \UspTheme::activeUrl('processos');

        if ($request->ajax()) {
            # preenche os dados do form de edição de um processo
            return Processo::find($id);
        } else {
            # desativado por enquanto
            return false;
            $setor = Processo::find($id);
            return view('processos.show', compact('processo'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('admin');
        $request->validate(Processo::rules);

        $processo = Processo::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($processo->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * Por enquanto somente para admin (masaki, 12/2020)
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate(Processo::rules);

        $processo = Processo::find($id);
        $processo->fill($request->all());
        $processo->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($processo->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('admin');

        $processo = Processo::find($id);
        $processo->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }
}
