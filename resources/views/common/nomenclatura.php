<?php
  if ($selecao->categoria->nome == 'Aluno Especial') {
    $inscricao_ou_matricula = 'matrícula';
    $inscricao_ou_matricula_plural = 'matrículas';
    $inscricao_ou_matricula_plural_passivo = 'matriculados';
    $objetivo = 'aluno especial';
  } elseif ($selecao->programa->matricula) {
    $inscricao_ou_matricula = 'matrícula';
    $inscricao_ou_matricula_plural = 'matrículas';
    $inscricao_ou_matricula_plural_passivo = 'matriculados';
    $objetivo = 'o programa ' . $selecao->programa->nome;
  } else {
    $inscricao_ou_matricula = 'inscrição';
    $inscricao_ou_matricula_plural = 'inscrições';
    $inscricao_ou_matricula_plural_passivo = 'inscritos';
    $objetivo = 'o processo seletivo ' . $selecao->nome;
  }
