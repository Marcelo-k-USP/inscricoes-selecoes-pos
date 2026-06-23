<?php

namespace App\Utils;

use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\Inscricao;

class ClasseUtils
{
    public static function obterClasseNomeFormatada(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'seleção';
            case 'SolicitacaoIsencaoTaxa':
                return 'solicitação de isenção de taxa';
            case 'Inscricao':
                return 'inscrição';
        }
    }

    public static function obterClasseNomePlural(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'selecoes';
            case 'SolicitacaoIsencaoTaxa':
                return 'solicitacoesisencaotaxa';
            case 'Inscricao':
                return 'inscricoes';
        }
    }

    public static function obterClasseNomePluralAcentuado(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'Seleções';
            case 'SolicitacaoIsencaoTaxa':
                return 'Solicitações de Isenção de Taxa';
            case 'Inscricao':
                return 'Inscrições';
        }
    }

    public static function obterClasseNomeAbreviada(string $classe_nome, ?Selecao $selecao = null) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'Sel';
            case 'SolicitacaoIsencaoTaxa':
                return 'SolicIsenc';
            case 'Inscricao':
                return ((($selecao->categoria->nome != 'Aluno Especial') && !$selecao->isMatricula()) ? 'Insc' : 'Matr');
        }
    }

    public static function obterClasseNomeAbreviadaPlural(string $classe_nome, ?Selecao $selecao = null) {
        if ($classe_nome == 'SolicitacaoIsencaoTaxa')
                return 'SolicsIsenc';

        return self::obterClasseNomeAbreviada($classe_nome, $selecao) . 's';
    }

    public static function obterClasse(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return Selecao::class;
            case 'SolicitacaoIsencaoTaxa':
                return SolicitacaoIsencaoTaxa::class;
            case 'Inscricao':
                return Inscricao::class;
        }
    }
}
