<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TipoArquivo extends Model
{
    use HasFactory;

    # tiposarquivo não segue convenção do laravel para nomes de tabela
    protected $table = 'tiposarquivo';

    protected $fillable = [
        'classe_nome',
        'nome',
        'obrigatorio',
        'minimo',
        'aluno_especial',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'classe_nome',
            'label' => 'Para',
            'type' => 'select',
            'data' => ['Seleções' => 'Seleções', 'Solicitações de Isenção de Taxa' => 'Solicitações de Isenção de Taxa', 'Inscrições' => 'Inscrições'],    // repete chave e valor, para que no select os values das options sejam também o texto
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'obrigatorio',
            'label' => 'Obrigatório?',
            'type' => 'checkbox',
        ],
        [
            'name' => 'minimo',
            'label' => 'Mínimo',
            'type' => 'integer',
        ],
        [
            'name' => 'aluno_especial',
            'label' => 'Aluno Especial',
            'type' => 'checkbox',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        $fields = self::fields;
        foreach ($fields as &$field) {
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    /**
     * retorna todos os tipos de arquivo
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $tiposarquivo = self::get();
        $ret = [];
        foreach ($tiposarquivo as $tipoarquivo)
            if (Gate::allows('tiposarquivo.view', $linhapesquisa))
                $ret[$tipoarquivo->id] = $tipoarquivo->nome;
        return $ret;
    }

    public static function obterTiposArquivoPossiveis(string $classe_nome, $niveis, ?int $programa_id)
    {
        switch ($classe_nome) {
            case 'Selecao':
                // todos os tipos de arquivo possíveis para seleções
                return self::where('classe_nome', 'Seleções')->get();

            case 'SolicitacaoIsencaoTaxa':
                // todos os tipos de arquivo possíveis para solicitações de isenção de taxa
                return self::where('classe_nome', 'Solicitações de Isenção de Taxa')->get();

            case 'Inscricao':
                // todos os tipos de arquivo possíveis para inscrições
                return self::where('classe_nome', 'Inscrições')->where(function ($query) use ($niveis, $programa_id) {
                    if ($niveis->isEmpty())
                        $query->where('aluno_especial', true);
                    else
                        // se houver combinação de nível com programa, se restringe a ela
                        $query->whereHas('niveisprogramas', function ($query) use ($niveis, $programa_id) {{
                            $query->whereIn('nivel_id', function ($query) use ($niveis) {
                                $query->select('id')->from('niveis')->whereIn('nome', $niveis->pluck('nome'));
                            })->where('programa_id', $programa_id);
                        }});
                })->get();
        }
    }

    public static function obterTiposArquivoDaSelecao(string $classe_nome, $niveis, Selecao $selecao)
    {
        $programa_id = $selecao->programa_id;
        switch ($classe_nome) {
            case 'SolicitacaoIsencaoTaxa':
                // todos os tipos de arquivo para solicitações de isenção de taxa nesta seleção
                return $selecao->tiposarquivo()->where('classe_nome', 'Solicitações de Isenção de Taxa')->get();

            case 'Inscricao':
                // todos os tipos de arquivo para inscrições nesta seleção
                return $selecao->tiposarquivo()->where('classe_nome', 'Inscrições')->where(function ($query) use ($niveis, $programa_id) {
                    if ($niveis->isEmpty())
                        $query->where('aluno_especial', true);
                    else
                        // se houver combinação de nível com programa, se restringe a ela
                        $query->whereHas('niveisprogramas', function ($query) use ($niveis, $programa_id) {{
                            $query->whereIn('nivel_id', function ($query) use ($niveis) {
                                $query->select('id')->from('niveis')->whereIn('nome', $niveis->pluck('nome'));
                            })->where('programa_id', $programa_id);
                        }});
                })->get();
        }
    }

    /**
     * Lista os tipos de arquivo autorizados para o usuário
     */
    public static function listarTiposArquivo()
    {
        if (session('perfil') != 'gerente')
                return self::query();

        if (DB::table('user_programa')    // não dá pra partir de $this->, pelo fato de programa_id ser null na tabela relacional
                ->where('user_id', Auth::id())
                ->whereIn('funcao', ['Serviço de Pós-Graduação', 'Coordenador de Pós-Graduação'])
                ->exists())
            return self::query();

        return self::whereHas('niveisprogramas', function ($query) {
            $query->whereIn('programa_id', Auth::user()->listarProgramasGerenciados()->pluck('id'));
        });
    }

    /**
     * relacionamento com seleções
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'selecao_tipoarquivo', 'tipoarquivo_id', 'selecao_id')->withTimestamps();
    }

    /**
     * relacionamento com combinações de níveis com programas
     */
    public function niveisprogramas()
    {
        return $this->belongsToMany('App\Models\NivelPrograma', 'tipoarquivo_nivelprograma', 'tipoarquivo_id', 'nivelprograma_id')->withTimestamps();
    }

    /**
     * relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->hasMany('App\Models\Arquivo', 'tipoarquivo_id');
    }
}
