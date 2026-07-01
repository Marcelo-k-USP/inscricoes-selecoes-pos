@if (in_array($matricula->estado, ['Aguardando Envio', 'Em Pré-Avaliação', 'Em Avaliação']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $matricula->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($matricula->estado, ['Enviada', 'Pré-Aprovada', 'Aprovada']))
  <span class="text-success" data-toggle="tooltip" title="{{ $matricula->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($matricula->estado, ['Pré-Rejeitada', 'Rejeitada']))
  <span class="text-danger" data-toggle="tooltip" title="{{ $matricula->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
