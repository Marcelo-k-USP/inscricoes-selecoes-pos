<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
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
        $this->authorize('categorias.viewAny');
        \UspTheme::activeUrl('categorias');

        $categorias = Categoria::all();
        $fields = Categoria::getFields();

        if ($request->ajax()) {
            // formatado para datatables
            #return response(['data' => $categorias]);
        } else {
            $modal['url'] = 'categorias';
            $modal['title'] = 'Editar Categoria';
            $rules = CategoriaRequest::rules;
            return view('categorias.tree', compact('categorias', 'fields', 'modal', 'rules'));
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
        $this->authorize('categorias.viewAny');
        \UspTheme::activeUrl('categorias');

        if ($request->ajax())
            return Categoria::find($id);    // preenche os dados do form de edição de uma categoria
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriaRequest $request)
    {
        $this->authorize('categorias.create');

        $validator = Validator::make($request->all(), CategoriaRequest::rules, CategoriaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $categoria = Categoria::create($request->all());

        $request->session()->flash('alert-success', 'Dados adicionados com sucesso');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriaRequest $request, $id)
    {
        $this->authorize('categorias.update');

        $validator = Validator::make($request->all(), CategoriaRequest::rules, CategoriaRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $categoria = Categoria::find($id);
        $categoria->fill($request->all());
        $categoria->save();

        $request->session()->flash('alert-success', 'Dados editados com sucesso');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaRequest $request, $id)
    {
        $this->authorize('categorias.delete');

        $categoria = Categoria::find($id);
        if ($categoria->selecoes()->exists()) {
            $request->session()->flash('alert-danger', 'Há seleções para esta categoria!');
            return back();
        }
        $categoria->delete();

        $request->session()->flash('alert-success', 'Dados removidos com sucesso!');
        return back();
    }
}
