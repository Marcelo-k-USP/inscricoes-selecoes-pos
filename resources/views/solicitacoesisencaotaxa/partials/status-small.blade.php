@if (in_array($solicitacaoisencaotaxa->estado, ['Aguardando Comprovação', 'Isenção de Taxa Em Avaliação']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Solicitada', 'Isenção de Taxa Aprovada']))
  <span class="text-success" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Rejeitada']))
  <span class="text-danger" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
