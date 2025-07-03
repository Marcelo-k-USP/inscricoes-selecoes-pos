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
        'link_acompanhamento_especiais',
        'email_servicoposgraduacao',
        'email_secaoinformatica',
        'email_gerenciamentosite',
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
        [
            'name' => 'link_acompanhamento_especiais',
            'label' => 'Link de Acompanhamento para Alunos Especiais',
        ],
        [
            'name' => 'email_servicoposgraduacao',
            'label' => 'E-mail do Serviço de Pós-Graduação',
        ],
        [
            'name' => 'email_secaoinformatica',
            'label' => 'E-mail da Seção de Informática',
        ],
        [
            'name' => 'email_gerenciamentosite',
            'label' => 'E-mail do Gerenciamento do Site',
        ],
    ];

    // uso no crud generico
    public static function getFields()
    {
        return self::fields;
    }
}
