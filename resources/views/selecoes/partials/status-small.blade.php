@if (in_array($selecao->estado, ['Em Elaboração', 'Aguardando Início']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Período de Solicitações de Isenção de Taxa')
  <span class="text-success" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Período de Inscrições')
  <span class="text-success" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Encerrada')
  <span class="text-danger" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
