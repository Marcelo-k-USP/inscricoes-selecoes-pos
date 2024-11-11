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
    
    /**
     * The attributes that should be mutated to dates.
     * https://laravel.com/docs/8.x/eloquent-mutators#date-casting
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
        'atualizadaEm' => 'date:d/m/Y',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['atualizadoEm'];

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
