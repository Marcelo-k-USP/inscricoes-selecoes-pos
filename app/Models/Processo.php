<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Processo extends Model
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
     * retorna todos os processos
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $processos = SELF::get();
        $ret = [];
        foreach ($processos as $processo) {
            if (Gate::allows('processos.view', $processo)) {
            $ret[$processo->id] = $processo->nome;
            }
        }
        return $ret;
    }

    /**
     * Menu Processos, lista os processos que o usuário pode ver
     *
     * @return coleção de processos
     */
    public static function listarProcessos()
    {
        return SELF::get();
    }

    /**
     * Processo possui seleções
     */
    public function selecoes()
    {
        return $this->hasMany('App\Models\Selecao');
    }

    /**
     * Relacionamento n:n com user, atributo funcao: Gerente, Atendente
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_processo')
            ->orderBy('users.name')
            ->withTimestamps();
    }
}
