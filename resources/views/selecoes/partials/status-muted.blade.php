@if (in_array($selecao->estado, ['Em Elaboração', 'Aguardando Início das Solicitações de Isenção de Taxa', 'Aguardando Início das Inscrições']))
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Período de Solicitações de Isenção de Taxa')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Período de Inscrições')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Encerrada')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@endif
