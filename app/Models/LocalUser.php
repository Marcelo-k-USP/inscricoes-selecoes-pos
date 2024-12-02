<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class LocalUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'codpes',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'name',
            'label' => 'Nome',
        ],
        [
            'name' => 'codpes',
            'label' => 'Nome de Usuário',
        ],
        [
            'name' => 'email',
            'label' => 'E-mail',
        ],
        [
            'name' => 'password',
            'label' => 'Senha',
        ],
    ];

    public static function getFields()
    {
        $fields = SELF::fields;
        return $fields;
        // foreach ($fields as &$field) {
        //     if (substr($field['name'], -3) == '_id') {
        //         $class = '\\App\\Models\\' . $field['model'];
        //         $field['data'] = $class::allToSelect();
        //     }
        // }
        // return $fields;
    }
}
