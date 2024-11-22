<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

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
            return view('categorias.tree', compact('categorias', 'fields', 'modal'));
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
        \UspTheme::activeUrl('categorias');

        if ($request->ajax()) {
            # preenche os dados do form de edição de uma categoria
            return Categoria::find($id);
        } else {
            # desativado por enquanto
            return false;
            return view('categorias.show', compact('categoria'));
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
        $request->validate(Categoria::rules);

        $categoria = Categoria::create($request->all());

        $request->session()->flash('alert-info', 'Dados adicionados com sucesso');
        return Redirect::to(URL::previous() . "#" . strtolower($categoria->id));
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
        $request->validate(Categoria::rules);

        $categoria = Categoria::find($id);
        $categoria->fill($request->all());
        $categoria->save();

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
