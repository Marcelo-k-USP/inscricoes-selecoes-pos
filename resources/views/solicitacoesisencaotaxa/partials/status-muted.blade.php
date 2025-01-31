@if ($solicitacaoisencaotaxa->estado == 'Aguardando Envio')
  <span class="badge badge-light text-secondary"> {{ $solicitacaoisencaotaxa->estado }} </span>
@elseif ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Solicitada')
  <span class="badge badge-light text-secondary"> {{ $solicitacaoisencaotaxa->estado }} </span>
@elseif ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa em Avaliação')
  <span class="badge badge-light text-secondary"> {{ $solicitacaoisencaotaxa->estado }} </span>
@elseif ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Aprovada')
  <span class="badge badge-light text-secondary"> {{ $solicitacaoisencaotaxa->estado }} </span>
@elseif ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $solicitacaoisencaotaxa->estado }} </span>
@endif
