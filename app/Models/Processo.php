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
     * Menu Processos, lista os processos que o usuário pode ver
     *
     * @return coleção de processos
     */
    public static function listarProcessos()
    {
        return SELF::get();
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
