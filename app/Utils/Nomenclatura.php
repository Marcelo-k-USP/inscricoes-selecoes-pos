<?php

namespace App\Utils;

class Nomenclatura
{
    public static function InscricaoOuMatricula(): string
    {
        return (request()->segment(1) === 'inscricoes' ? 'inscrição' : 'matrícula');
    }

    public static function InscricaoOuMatriculaPlural(): string
    {
        return (request()->segment(1) === 'inscricoes' ? 'inscrições' : 'matrículas');
    }
}
