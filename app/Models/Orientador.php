<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Orientador extends Model
{
    use HasFactory;

    # orientadores não segue convenção do laravel para nomes de tabela
    protected $table = 'orientadores';

    protected $fillable = [
        'codpes',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'codpes',
            'label' => 'Orientador',
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
     * relacionamento com linhas de pesquisa
     */
    public function linhaspesquisa()
    {
        return $this->belongsToMany('App\Models\LinhaPesquisa', 'orientador_linhapesquisa', 'orientador_id', 'linhapesquisa_id')->withTimestamps();
    }
}
