<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    /**
     * relacionamento com seleção
     */
    public function selecao()
    {
        return $this->belongsToMany('App\Models\Selecao', 'arquivo_selecao')->withPivot('tipo')->withTimestamps();
    }

    /**
     * relacionamento com inscrição
     */
    public function inscricao()
    {
        return $this->belongsToMany('App\Models\Inscricao', 'arquivo_inscricao')->withPivot('tipo')->withTimestamps();
    }
}
