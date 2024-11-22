<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\Selecao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArquivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_modelo = fixJson($request->tipo_modelo);
        $tipo_modelo_plural = $this->obtemModeloPlural($tipo_modelo);
        $classe_modelo = $this->obtemClasseModelo($tipo_modelo);
        $modelo = $classe_modelo::find($request->modelo_id);
        
        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:' . config('selecoes-pos.upload_max_filesize'),
            'modelo_id' => 'required|integer|exists:' . $tipo_modelo_plural . ',id',
        ]);
        $this->authorize($tipo_modelo_plural . '.update', $modelo);

        foreach ($request->arquivo as $arq) {
            $arquivo = new Arquivo;
            $arquivo->user_id = \Auth::user()->id;
            $arquivo->nome_original = $arq->getClientOriginalName();
            $arquivo->caminho = $arq->store('./arquivos/' . $modelo->created_at->year);
            $arquivo->mimeType = $arq->getClientMimeType();
            $arquivo->save();
            
            $arquivo->{$tipo_modelo_plural}()->attach($modelo->id, ['tipo' => $request->tipo_arquivo]);
        }

        $request->session()->flash('alert-info', 'Arquivo(s) adicionado(s) com sucesso');
        
        \UspTheme::activeUrl($tipo_modelo_plural);
        return view($tipo_modelo_plural . '.edit', $this->monta_compact($classe_modelo, $modelo, $tipo_modelo, 'edit'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        if (Arquivo::find($arquivo->id)->selecoes()->exists())
            $tipo_modelo = 'Seleção';
        elseif (Arquivo::find($arquivo->id)->inscricoes()->exists())
            $tipo_modelo = 'Inscrição';
        $tipo_modelo_plural = $this->obtemModeloPlural($tipo_modelo);

        $this->authorize($tipo_modelo_plural . '.view');
        
        //https://stackoverflow.com/questions/39329299/laravel-file-downloaded-from-storage-folder-gets-corrupted
        ob_end_clean();

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function edit(Arquivo $arquivo)
    {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $tipo_modelo = fixJson($request->tipo_modelo);
        $tipo_modelo_plural = $this->obtemModeloPlural($tipo_modelo);
        $classe_modelo = $this->obtemClasseModelo($tipo_modelo);
        $modelo = $classe_modelo::find($request->modelo_id);
        
        $request->validate(
            ['nome_arquivo' => 'required'],
            ['nome_arquivo.required' => 'O nome do arquivo é obrigatório!']
        );
        $this->authorize($tipo_modelo_plural . '.update', $modelo);

        $nome_antigo = $arquivo->nome_original;
        $extensao = pathinfo($nome_antigo, PATHINFO_EXTENSION);
        $arquivo->nome_original = $request->nome_arquivo . '.' . $extensao;
        $arquivo->update();

        $request->session()->flash('alert-info', 'Arquivo renomeado com sucesso');
        
        \UspTheme::activeUrl($tipo_modelo_plural);
        return view($tipo_modelo_plural . '.edit', $this->monta_compact($classe_modelo, $modelo, $tipo_modelo, 'edit'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $tipo_modelo = fixJson($request->tipo_modelo);
        $tipo_modelo_plural = $this->obtemModeloPlural($tipo_modelo);
        $classe_modelo = $this->obtemClasseModelo($tipo_modelo);
        $modelo = $classe_modelo::find($request->modelo_id);
        
        $this->authorize($tipo_modelo_plural . '.update', $modelo);

        if (Storage::exists($arquivo->caminho))
            Storage::delete($arquivo->caminho);

        $arquivo->{$tipo_modelo_plural}()->detach($modelo->id, ['tipo' => $request->tipo_arquivo]);
        $arquivo->delete();

        $request->session()->flash('alert-info', 'Arquivo removido com sucesso');
        
        \UspTheme::activeUrl($tipo_modelo_plural);
        return view($tipo_modelo_plural . '.edit', $this->monta_compact($classe_modelo, $modelo, $tipo_modelo, 'edit'));
    }

    private function obtemModeloPlural($tipo_modelo) {
        switch ($tipo_modelo) {
            case 'Seleção':
                return 'selecoes';
            case 'Inscrição':
                return 'inscricoes';
        }
    }

    private function obtemClasseModelo($tipo_modelo) {
        switch ($tipo_modelo) {
            case 'Seleção':
                return Selecao::class;
            case 'Inscrição':
                return Inscricao::class;
        }
    }

    private function monta_compact($classe_modelo, $modelo, $tipo_modelo, $modo) {
        $data = (object) ('App\\Http\\Controllers\\' . class_basename($classe_modelo) . 'Controller')::$data;
        $linhaspesquisa = LinhaPesquisa::all();
        $max_upload_size = config('selecoes-pos.upload_max_filesize');
    
        return compact('data', 'modelo', 'tipo_modelo', 'modo', 'linhaspesquisa', 'max_upload_size');
    }
}
