<?php

namespace App\Models;

use App\Observers\ArquivoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        Arquivo::observe(ArquivoObserver::class);
    }

    /**
     * relacionamento com seleção
     */
    public function selecoes()
    {
        return $this->belongsToMany('App\Models\Selecao', 'arquivo_selecao')->withPivot('tipo')->withTimestamps();
    }

    /**
     * relacionamento com solicitação de isenção de taxa
     */
    public function solicitacoesisencaotaxa()
    {
        return $this->belongsToMany('App\Models\SolicitacaoIsencaoTaxa', 'arquivo_solicitacaoisencaotaxa', 'arquivo_id', 'solicitacaoisencaotaxa_id')->withTimestamps();    // se eu não especificar o nome do campo como solicitacaoisencaotaxa_id, o Laravel vai pensar que é solicitacao_isencao_taxa_id, e vai dar erro
    }

    /**
     * relacionamento com inscrição
     */
    public function inscricoes()
    {
        return $this->belongsToMany('App\Models\Inscricao', 'arquivo_inscricao')->withPivot('tipo')->withTimestamps();
    }

    /**
     * Relacionamento: arquivo tem um tipo de arquivo
     */
    public function tipoarquivo()
    {
        return $this->belongsTo('App\Models\TipoArquivo', 'tipoarquivo_id');
    }
}
