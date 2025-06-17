@if (in_array($selecao->estado, ['Em Elaboração', 'Aguardando Início das Solicitações de Isenção de Taxa e das Inscrições', 'Aguardando Início das Solicitações de Isenção de Taxa', 'Aguardando Início das Inscrições']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições', 'Período de Solicitações de Isenção de Taxa', 'Período de Inscrições']))
  <span class="text-success" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Encerrada')
  <span class="text-danger" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
