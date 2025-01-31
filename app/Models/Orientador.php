<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Uspdev\Replicado\Pessoa;

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

    public static function obterNome($codpes)
    {
        $orientador = self::where('codpes', $codpes)->first();
        if (!$orientador)
            return null;

        if ($orientador->externo)
            return $orientador->nome;
        else
            return Pessoa::obterNome($codpes);
    }

    public static function obterEmail($codpes)
    {
        $orientador = self::where('codpes', $codpes)->first();
        if (!$orientador)
            return null;

        if ($orientador->externo)
            return $orientador->email;
        else
            return Pessoa::email($codpes);
    }

    /**
     * relacionamento com linhas de pesquisa/temas
     */
    public function linhaspesquisa()
    {
        return $this->belongsToMany('App\Models\LinhaPesquisa', 'linhapesquisa_orientador', 'orientador_id', 'linhapesquisa_id')->withTimestamps();
    }
}
