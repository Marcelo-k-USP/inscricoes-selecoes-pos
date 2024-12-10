<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Parametro extends Model
{
    use HasFactory;

    protected $fillable = [
        'boleto_valor',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'boleto_valor',
            'label' => 'Valor do Boleto de Inscrição (R$)',
            'type' => 'number',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        return SELF::fields;
    }
}
