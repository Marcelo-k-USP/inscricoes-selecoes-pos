<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class NivelPrograma extends Model
{
    use HasFactory;

    # nivel_programa não segue convenção do laravel para nomes de tabela
    protected $table = 'nivel_programa';

    public static function obterNiveisProgramasPossiveis()
    {
        // todas as combinações de níveis em programas possíveis para este usuário
        $programas = Auth::user()->listarProgramasGerenciados();
        return self::whereIn('programa_id', $programas->pluck('id'))->get();
    }

    public static function obterNiveisProgramasDoTipoArquivo(TipoArquivo $tipoarquivo)
    {
        // todas as combinações de níveis em programas neste tipo de arquivo
        $programas = Auth::user()->listarProgramasGerenciados();
        return $tipoarquivo->niveisprogramas->filter(function ($nivelprograma) use ($programas) {
            return $programas->pluck('id')->contains($nivelprograma->programa_id);
        });
    }

    /**
     * relacionamento com nível
     */
    public function nivel()
    {
        return $this->belongsTo('App\Models\Nivel', 'nivel_id');
    }

    /**
     * relacionamento com programa
     */
    public function programa()
    {
        return $this->belongsTo('App\Models\Programa', 'programa_id');
    }

    /**
     * relacionamento com tipos de arquivo
     */
    public function tiposarquivo()
    {
        return $this->belongsToMany('App\Models\TipoArquivo', 'tipoarquivo_nivelprograma', 'nivelprograma_id', 'tipoarquivo_id')->withTimestamps();
    }
}
