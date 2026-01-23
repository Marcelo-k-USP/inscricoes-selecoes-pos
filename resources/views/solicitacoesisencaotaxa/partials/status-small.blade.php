@if (in_array($solicitacaoisencaotaxa->estado, ['Aguardando Envio', 'Isenção de Taxa em Avaliação']))
  <span class="text-warning" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Solicitada', 'Isenção de Taxa Aprovada', 'Isenção de Taxa Aprovada Após Recurso']))
  <span class="text-success" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Rejeitada']))
  <span class="text-danger" data-toggle="tooltip" title="{{ $solicitacaoisencaotaxa->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
