<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class LinhaPesquisa extends Model
{
    use HasFactory;

    # linhaspesquisa não segue convenção do laravel para nomes de tabela
    protected $table = 'linhaspesquisa';

    protected $fillable = [
        'nome',
        'codpes_docente',
        'programa_id',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'codpes_docente',
            'label' => 'Docente Responsável',
        ],
        [
            'name' => 'programa_id',
            'label' => 'Programa',
            'type' => 'select',
            'model' => 'Programa',
            'data' => [],
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        $fields = SELF::fields;
        foreach ($fields as &$field) {
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    /**
     * retorna todas as linhas de pesquisa autorizadas para o usuário
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $linhaspesquisa = SELF::get();
        $ret = [];
        foreach ($linhaspesquisa as $linhapesquisa)
            if (Gate::allows('linhaspesquisa.view', $linhapesquisa))
                $ret[$linhapesquisa->id] = $linhapesquisa->nome;
        return $ret;
    }

    public static function listarLinhasPesquisa(Programa $programa)
    {
        if ((!is_null($programa)) && ($programa->id > 0))
            return SELF::where('programa_id', $programa->id)->get();
        else
            return SELF::get();
    }

    /**
     * relacionamento com seleções
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'linhapesquisa_selecao', 'linhapesquisa_id', 'selecao_id')->withTimestamps();
    }

    /**
     * Relacionamento: linha de pesquisa pertence a programa
     */
    public function programa()
    {
        return $this->belongsTo('App\Models\Programa');
    }
}
