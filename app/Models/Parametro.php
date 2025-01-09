<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Parametro extends Model
{
    use HasFactory;

    protected $fillable = [
        'boleto_codigo_fonte_recurso',
        'boleto_estrutura_hierarquica',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'boleto_codigo_fonte_recurso',
            'label' => 'Código Fonte do Recurso para Boleto',
            'type' => 'integer',
        ],
        [
            'name' => 'boleto_estrutura_hierarquica',
            'label' => 'Estrutura Hierárquica para Boleto',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        return self::fields;
    }
}
