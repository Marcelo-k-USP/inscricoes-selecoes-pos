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
            $ret[$tipoarquivo->id] = $tipoarquivo->nome;
        return $ret;
    }

    /**
     * relacionamento com seleções
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'selecao_tipoarquivo', 'tipoarquivo_id', 'selecao_id')->withTimestamps();
    }

    /**
     * relacionamento com níveis
     */
    public function niveis()
    {
        return $this->belongsToMany('App\Models\Nivel', 'tipoarquivo_nivel', 'tipoarquivo_id', 'nivel_id')->withTimestamps();
    }

    /**
     * relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->hasMany('App\Models\Arquivo', 'tipoarquivo_id');
    }
}
