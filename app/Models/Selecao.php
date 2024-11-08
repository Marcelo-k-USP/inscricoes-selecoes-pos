<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Selecao extends Model
{
    use HasFactory;

    # materiais não segue convenção do laravel para nomes de tabela
    protected $table = 'selecoes';

    protected $fillable = [
        'nome',
        'descricao',
        'processo_id',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'processo_id',
            'label' => 'Processo',
            'type' => 'select',
            'model' => 'Processo',
            'data' => [],
        ],
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
     * Menu Seleções, lista as seleções
     *
     * @return coleção de seleções
     */
    public static function listarSelecoes()
    {
        return SELF::get();
    }

    /**
     * Relacionamento: seleção pertence a processo
     */
    public function processo()
    {
        return $this->belongsTo('App\Models\Processo');
    }
}
