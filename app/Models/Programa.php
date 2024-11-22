<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Programa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public const rules = [
        'nome' => ['required', 'max:100'],
    ];
    
    // uso no crud generico
    protected const fields = [
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'descricao',
            'label' => 'Descrição',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        return SELF::fields;
    }

    /**
     * retorna todos os programas
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $programas = SELF::get();
        $ret = [];
        foreach ($programas as $programa)
            if (Gate::allows('programas.view', $programa))
                $ret[$programa->id] = $programa->nome;
        return $ret;
    }

    /**
     * Programa possui seleções
     */
    public function selecoes()
    {
        return $this->hasMany('App\Models\Selecao');
    }

    /**
     * Programa possui linhas de pesquisa
     */
    public function linhaspesquisa()
    {
        return $this->hasMany('App\Models\LinhaPesquisa');
    }
}
