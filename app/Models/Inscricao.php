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
    ];

    /**
     * Lista as inscrições
     *
     * Vamos considerar chamados de seleções encerradas
     *
     * @return Collection
     */
    public static function listarInscricoes()
    {
        $inscricoes = SELF::get();
        return $inscricoes;
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
