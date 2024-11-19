<?php

namespace App\Models;

use App\Models\Selecao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Inscricao extends Model
{
    use HasFactory;

    # inscrições não segue convenção do laravel para nomes de tabela
    protected $table = 'inscricoes';
    
    protected $fillable = [
        'selecao_id',
    ];

    // uso no crud generico
    protected const fields = [
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
     * Retorna os tipos de arquivo possíveis na seleção.
     */
    public static function tiposArquivo()
    {
        return ['tipo 1', 'tipo 2'];
    }
    
    /**
     * Valores possiveis para pivot do relacionamento com users
     */
    #
    public static function pessoaPapeis($formSelect = false)
    {
        if ($formSelect) {
            return [
                'Autor' => 'Autor',
            ];
        } else {
            return ['Autor'];
        }
    }

    /**
     * Lista as inscrições
     *
     * @return Collection
     */
    public static function listarInscricoes()
    {
        $inscricoes = SELF::get();
        return $inscricoes;
    }

    /**
     * Mostra as pessoas que tem vínculo com a inscrição.
     *
     * Se informado $pivot, retorna somente o 1o. User, se não, retorna a lista completa
     *
     * @param $pivot Papel da pessoa na inscrição (autor, null = todos)
     * @return App\Models\User|Collection
     */
    public function pessoas($pivot = null)
    {
        if ($pivot) {
            return $this->users()->wherePivot('papel', $pivot)->first();
        } else {
            return $this->users()->withPivot('papel');
        }
    }
    
    /**
     * Accessor: retorna a data da última atualização da inscrição
     */
    public function getAtualizadaEmAttribute()
    {
        return $this->updated_at;
    }

    /**
     * relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->belongsToMany('App\Models\Arquivo', 'arquivo_inscricao')->withPivot('tipo')->withTimestamps();
    }

    /**
     * relacionamento com users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_inscricao')->withTimestamps();
    }

    /**
     * relacionamento com seleção
     */
    public function selecao()
    {
        return $this->belongsTo(Selecao::class);
    }
}
