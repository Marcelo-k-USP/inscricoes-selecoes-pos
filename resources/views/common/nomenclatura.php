<?php

  if ($selecao->categoria->nome == 'Aluno Especial') {
    $inscricao_ou_matricula = 'matrícula';
    $inscricao_ou_matricula_plural = 'matrículas';
    $objetivo = 'aluno especial';
  } elseif ($selecao->programa->matricula) {
    $inscricao_ou_matricula = 'matrícula';
    $inscricao_ou_matricula_plural = 'matrículas';
    $objetivo = 'o programa ' . $selecao->programa->nomeCompleto();
  } else {
    $inscricao_ou_matricula = 'inscrição';
    $inscricao_ou_matricula_plural = 'inscrições';
    $objetivo = 'o processo seletivo ' . $selecao->nome;
  }
