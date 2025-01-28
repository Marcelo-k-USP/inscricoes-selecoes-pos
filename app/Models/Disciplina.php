<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'sigla',
        'nome',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'sigla',
            'label' => 'Sigla',
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
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
     * retorna todas as disciplinas autorizadas para o usuário
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $disciplinas = self::get();
        $ret = [];
        foreach ($disciplinas as $disciplina)
            if (Gate::allows('disciplinas.view', $disciplina))
                $ret[$disciplina->id] = $disciplina->nome;
        return $ret;
    }

    public static function listarDisciplinas()
    {
        return self::get();
    }

    /**
     * relacionamento com seleções
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'disciplina_selecao', 'disciplina_id', 'selecao_id')->withTimestamps();
    }
}
