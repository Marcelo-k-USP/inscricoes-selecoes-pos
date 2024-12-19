<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\LinhaPesquisa;
use App\Models\Selecao;
use App\Utils\JSONForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ArquivoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function show(Arquivo $arquivo)
    {
        if (Arquivo::find($arquivo->id)->selecoes()->exists())
            $classe_nome = 'Selecao';
        elseif (Arquivo::find($arquivo->id)->inscricoes()->exists())
            $classe_nome = 'Inscricao';
        $this->authorize('arquivos.view', [$arquivo, $classe_nome]);

        ob_end_clean();    // https://stackoverflow.com/questions/39329299/laravel-file-downloaded-from-storage-folder-gets-corrupted

        return Storage::download($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $request->validate([
            'arquivo.*' => 'required|mimes:jpeg,jpg,png,pdf|max:' . config('selecoes-pos.upload_max_filesize'),
            'objeto_id' => 'required|integer|exists:' . $classe_nome_plural . ',id',
        ]);
        $this->authorize('arquivos.create', [$objeto, $classe_nome]);

        foreach ($request->arquivo as $arq) {
            $arquivo = new Arquivo;
            $arquivo->user_id = \Auth::user()->id;
            $arquivo->nome_original = $arq->getClientOriginalName();
            $arquivo->caminho = $arq->store('./arquivos/' . $objeto->created_at->year);
            $arquivo->mimeType = $arq->getClientMimeType();
            $arquivo->save();

            $arquivo->{$classe_nome_plural}()->attach($objeto->id, ['tipo' => $request->tipo_arquivo]);
        }

        switch ($classe_nome) {
            case 'Selecao':
                $objeto->atualizaStatus();
                $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');
                break;
            case 'Inscricao':
                $objeto->verificarArquivos();
        }

        $request->session()->flash('alert-success', 'Arquivo(s) adicionado(s) com sucesso');

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Arquivo $arquivo)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $request->validate(
            ['nome_arquivo' => 'required'],
            ['nome_arquivo.required' => 'O nome do arquivo é obrigatório!']
        );
        $this->authorize('arquivos.update', [$arquivo, $objeto, $classe_nome]);

        $nome_antigo = $arquivo->nome_original;
        $extensao = pathinfo($nome_antigo, PATHINFO_EXTENSION);
        $arquivo->nome_original = $request->nome_arquivo . '.' . $extensao;
        $arquivo->update();

        $request->session()->flash('alert-success', 'Arquivo renomeado com sucesso');

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request   $request
     * @param  \App\Models\Arquivo        $arquivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Arquivo $arquivo)
    {
        $classe_nome = fixJson($request->classe_nome);
        $classe_nome_plural = $this->obterClasseNomePlural($classe_nome);
        $classe = $this->obterClasse($classe_nome);
        $objeto = $classe::find($request->objeto_id);
        $form = $this->obterForm($classe_nome, $objeto);

        $this->authorize('arquivos.delete', [$arquivo, $objeto, $classe_nome]);

        if (Storage::exists($arquivo->caminho))
            Storage::delete($arquivo->caminho);

        $arquivo->{$classe_nome_plural}()->detach($objeto->id, ['tipo' => $request->tipo_arquivo]);
        $arquivo->delete();

        switch ($classe_nome) {
            case 'Selecao':
                $objeto->atualizaStatus();
                $objeto->estado = Selecao::where('id', $objeto->id)->value('estado');
                break;
            case 'Inscricao':
                $objeto->verificarArquivos();
        }

        $request->session()->flash('alert-success', 'Arquivo removido com sucesso');

        \UspTheme::activeUrl($classe_nome_plural);
        return view($classe_nome_plural . '.edit', $this->monta_compact($objeto, $classe_nome, $classe_nome_plural, $form, 'edit'));
    }

    private function obterClasseNomePlural(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'selecoes';
            case 'Inscricao':
                return 'inscricoes';
        }
    }

    private function obterClasse(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return Selecao::class;
            case 'Inscricao':
                return Inscricao::class;
        }
    }

    private function obterForm(string $classe_nome, object $objeto) {
        switch ($classe_nome) {
            case 'Selecao':
                return null;
            case 'Inscricao':
                $objeto->selecao->template = JSONForms::orderTemplate($objeto->selecao->template);
                return JSONForms::generateForm($objeto->selecao, $objeto);
        }
    }

    private function monta_compact(object $objeto, string $classe_nome, string $classe_nome_plural, $form, string $modo) {
        $data = (object) ('App\\Http\\Controllers\\' . $classe_nome . 'Controller')::$data;
        $linhaspesquisa = LinhaPesquisa::all();
        $max_upload_size = config('selecoes-pos.upload_max_filesize');

        return compact('data', 'objeto', 'classe_nome', 'classe_nome_plural', 'form', 'modo', 'linhaspesquisa', 'max_upload_size');
    }
}
