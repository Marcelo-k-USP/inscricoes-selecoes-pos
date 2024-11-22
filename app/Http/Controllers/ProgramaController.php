<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ProgramaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra lista de categorias
     */
    public function index(Request $request)
    {
        $this->authorize('programas.viewAny');
        \UspTheme::activeUrl('programas');
        
        $programas = Programa::all();
        $fields = Programa::getFields();

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $programas]);
        } else {
            $modal['url'] = 'programas';
            $modal['title'] = 'Editar Programa';
            return view('programas.tree', compact('programas', 'fields', 'modal'));
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
        \UspTheme::activeUrl('programas');

        if ($request->ajax()) {
            # preenche os dados do form de edição de um programa
            return Programa::find($id);
        } else {
            # desativado por enquanto
            return false;
            return view('programas.show', compact('programa'));
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
        $request->validate(Programa::rules);

        $programa = Programa::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($programa->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('admin');
        $request->validate(Programa::rules);

        $programa = Programa::find($id);
        $programa->fill($request->all());
        $programa->save();

        $request->session()->flash('alert-info', 'Dados editados com sucesso');
        return back();
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

        $programa = Programa::find($id);
        $programa->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }
}
