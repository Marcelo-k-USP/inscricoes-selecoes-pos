<?php

namespace App\Http\Controllers;

use App\Http\Requests\LimpezaDadosRequest;
use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LimpezaDadosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showForm()
    {
        $this->authorize('limpezadados.showForm');

        \UspTheme::activeUrl('limpezadados');
        return view('limpezadados.form');
    }

    public function run(LimpezaDadosRequest $request)
    {
        $this->authorize('limpezadados.run');

        $validator = Validator::make($request->all(), LimpezaDadosRequest::rules, LimpezaDadosRequest::messages);
        if ($validator->fails())
            return back()->withErrors($validator)->withInput();

        $data_limite = Carbon::createFromFormat('d/m/Y', $request->data_limite);

        // transaction para não ter problema de inconsistência do DB
        $db_transaction = DB::transaction(function () use ($data_limite) {

            // apaga todas as inscrições gravadas no banco de dados até essa data
            foreach (Inscricao::where('created_at', '<=', $data_limite)->get() as $inscricao) {
                $inscricao->arquivos()->detach();
                $inscricao->pessoas()->detach();
                $inscricao->delete();
            }

            // apaga todas as solicitações de isenção de taxa gravadas no banco de dados até essa data
            foreach (SolicitacaoIsencaoTaxa::where('created_at', '<=', $data_limite)->get() as $solicitacaoisencaotaxa) {
                $solicitacaoisencaotaxa->arquivos()->detach();
                $solicitacaoisencaotaxa->pessoas()->detach();
                $solicitacaoisencaotaxa->delete();
            }

            // apaga todas as seleções gravadas no banco de dados até essa data
            foreach (Selecao::where('created_at', '<=', $data_limite)->get() as $selecao) {
                $selecao->arquivos()->detach();
                $selecao->disciplinas()->detach();
                $selecao->linhaspesquisa()->detach();
                $selecao->motivosisencaotaxa()->detach();
                $selecao->niveislinhaspesquisa()->detach();
                $selecao->tiposarquivo()->detach();
                $selecao->delete();
            }

            // apaga todos os arquivos gravados no banco de dados até essa data
            foreach (Arquivo::where('created_at', '<=', $data_limite)->get() as $arquivo)
                $arquivo->delete();
        });

        // apaga todos os arquivos gravados no servidor até essa data
        $pasta_base = storage_path('app/arquivos');
        if (File::exists($pasta_base))
            foreach (File::directories($pasta_base) as $subpasta)
                foreach (File::files($subpasta) as $arquivo)
                    if (Carbon::createFromTimestamp(File::lastModified($arquivo))->lte($data_limite))
                        File::delete($arquivo);

        $request->session()->flash('alert-success', 'Operação realizada com sucesso');
        \UspTheme::activeUrl('limpezadados');
        return view('limpezadados.form');
    }
}
