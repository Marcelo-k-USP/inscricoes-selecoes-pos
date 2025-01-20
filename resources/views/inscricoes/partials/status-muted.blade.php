@if ($inscricao->estado == 'Aguardando Documentação')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Realizada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Em Pré-Avaliação')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Pré-Aprovada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Pré-Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Em Avaliação')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Aprovada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@endif
