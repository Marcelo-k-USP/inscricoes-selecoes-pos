<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramaRequest;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProgramaController extends Controller
{
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
        $this->authorize('programas.viewAny');
        \UspTheme::activeUrl('programas');

        if (!$request->ajax())
            return view('programas.tree', $this->monta_compact_index());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  string                     $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $id)
    {
        $this->authorize('programas.view', Programa::where('id', $id)->first());
        \UspTheme::activeUrl('programas');

        if ($request->ajax())
            return Programa::find((int) $id);    // preenche os dados do form de edição de um programa
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProgramaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramaRequest $request)
    {
        $this->authorize('programas.create');

        $validator = Validator::make($request->all(), ProgramaRequest::rules, ProgramaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $programa = Programa::create($request->all());

        $request->session()->flash('alert-success', 'Dados adicionados com sucesso');
        return view('programas.tree', $this->monta_compact_index());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProgramaRequest  $request
     * @param  string                              $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramaRequest $request, string $id)
    {
        $this->authorize('programas.update');

        $validator = Validator::make($request->all(), ProgramaRequest::rules, ProgramaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $programa = Programa::find((int) $id);
        $programa->fill($request->all());
        $programa->save();

        $request->session()->flash('alert-success', 'Dados editados com sucesso');
        return view('programas.tree', $this->monta_compact_index());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\ProgramaRequest  $request
     * @param  string                              $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramaRequest $request, string $id)
    {
        $this->authorize('programas.delete');

        $programa = Programa::find((int) $id);
        if ($programa->selecoes()->exists()) {
            $request->session()->flash('alert-danger', 'Há seleções para este programa!');
            return view('programas.tree', $this->monta_compact_index());
        }
        if ($programa->linhaspesquisa()->exists()) {
            $request->session()->flash('alert-danger', 'Há linhas de pesquisa/temas para este programa!');
            return view('programas.tree', $this->monta_compact_index());
        }
        $programa->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return view('programas.tree', $this->monta_compact_index());
    }

    private function monta_compact_index()
    {
        $programas = Programa::all();
        $fields = Programa::getFields();
        $modal['url'] = 'programas';
        $modal['title'] = 'Editar Programa';
        $rules = ProgramaRequest::rules;

        return compact('programas', 'fields', 'modal', 'rules');
    }
}
