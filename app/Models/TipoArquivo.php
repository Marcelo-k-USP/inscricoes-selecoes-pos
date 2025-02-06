<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public static function obterTiposArquivo(string $classe_nome, $niveis, $selecao)
    {
        switch ($classe_nome) {
            case 'Selecao':
                return self::where('classe_nome', 'Seleções')->get();    // todos os tipos de arquivo possíveis para seleções

            case 'SolicitacaoIsencaoTaxa':
                return $selecao->tiposarquivo->filter(function ($registro) {
                    return $registro->classe_nome === 'Solicitações de Isenção de Taxa';
                });    // todos os tipos de arquivo possíveis para solicitações de isenção de taxa desta seleção

            case 'Inscricao':
                return $selecao->tiposarquivo->filter(function ($registro) use ($niveis, $selecao) {
                    \Illuminate\Support\Facades\Log::info('$niveis: ' . json_encode($niveis));
                    return (($registro->classe_nome === 'Inscrições') &&
                            (
                                ($niveis->isEmpty() && $registro->aluno_especial) ||
                                (!$niveis->isEmpty() && $registro->niveisprogramas->contains(function ($nivelprograma) use ($niveis, $selecao) {
                                    return ($niveis->pluck('nome')->contains($nivelprograma->nivel->nome) && ($nivelprograma->programa->nome === $selecao->programa->nome));
                                }))
                            )
                           );    // se houver combinação de nível com programa, se restringe a ela
                });    // todos os tipos de arquivo possíveis para inscrições desta seleção
        }
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
