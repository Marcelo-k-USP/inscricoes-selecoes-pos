@if ($matricula->estado == 'Aguardando Envio')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Enviada')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Em Pré-Avaliação')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Pré-Aprovada')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Pré-Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Em Avaliação')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Aprovada')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@elseif ($matricula->estado == 'Rejeitada')
  <span class="badge badge-light text-secondary"> {{ $matricula->estado }} </span>
@endif
