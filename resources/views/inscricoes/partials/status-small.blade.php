@if (in_array($inscricao->estado, ['Aguardando Documentação', 'Em Avaliação']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $inscricao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($inscricao->estado, ['Realizada', 'Aprovada']))
  <span class="text-success" data-toggle="tooltip" title="{{ $inscricao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($inscricao->estado, ['Rejeitada']))
  <span class="text-danger" data-toggle="tooltip" title="{{ $inscricao->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
