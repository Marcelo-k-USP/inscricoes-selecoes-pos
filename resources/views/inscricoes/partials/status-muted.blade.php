@if ($inscricao->estado == 'Aguardando Documentação')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Realizada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Em Avaliação')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Aceita')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Pendente')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Cancelada')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@elseif ($inscricao->estado == 'Concluída')
  <span class="badge badge-light text-secondary"> {{ $inscricao->estado }} </span>
@endif
