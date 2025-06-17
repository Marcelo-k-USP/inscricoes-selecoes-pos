@if (in_array($selecao->estado, ['Em Elaboração', 'Aguardando Início das Solicitações de Isenção de Taxa e das Inscrições', 'Aguardando Início das Solicitações de Isenção de Taxa', 'Aguardando Início das Inscrições']))
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif (in_array($selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições', 'Período de Solicitações de Isenção de Taxa', 'Período de Inscrições']))
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Encerrada')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@endif
