<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class MotivoIsencaoTaxa extends Model
{
    use HasFactory;

    # motivosisencaotaxa não segue convenção do laravel para nomes de tabela
    protected $table = 'motivosisencaotaxa';

    protected $fillable = [
        'nome',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'nome',
            'label' => 'Nome',
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
     * retorna todos os motivos de isenção de taxa autorizados para o usuário
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $motivosisencaotaxa = self::get();
        $ret = [];
        foreach ($motivosisencaotaxa as $motivoisencaotaxa)
            if (Gate::allows('motivosisencaotaxa.view', $motivoisencaotaxa))
                $ret[$motivoisencaotaxa->id] = $motivoisencaotaxa->nome;
        return $ret;
    }

    public static function listarMotivosIsencaoTaxa()
    {
        return self::get();
    }

    /**
     * relacionamento com seleções
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'motivoisencaotaxa_selecao', 'motivoisencaotaxa_id', 'selecao_id')->withTimestamps();
    }
}
