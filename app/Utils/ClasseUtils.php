<?php

namespace App\Utils;

use App\Models\Inscricao;
use App\Models\Matricula;
use App\Models\Selecao;
use App\Models\SolicitacaoIsencaoTaxa;

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
            case 'Matricula':
                return 'matrícula';
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
            case 'Matricula':
                return 'matriculas';
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
            case 'Matricula':
                return 'Matrículas';
        }
    }

    public static function obterClasseNomeAbreviada(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'Sel';
            case 'SolicitacaoIsencaoTaxa':
                return 'SolicIsenc';
            case 'Inscricao':
                return 'Insc';
            case 'Matricula':
                return 'Matr';
        }
    }

    public static function obterClasseNomeAbreviadaPlural(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return 'Sels';
            case 'SolicitacaoIsencaoTaxa':
                return 'SolicsIsenc';
            case 'Inscricao':
                return 'Inscs';
            case 'Matricula':
                return 'Matrs';
        }
    }

    public static function obterClasse(string $classe_nome) {
        switch ($classe_nome) {
            case 'Selecao':
                return Selecao::class;
            case 'SolicitacaoIsencaoTaxa':
                return SolicitacaoIsencaoTaxa::class;
            case 'Inscricao':
                return Inscricao::class;
            case 'Matricula':
                return Matricula::class;
        }
    }
}
