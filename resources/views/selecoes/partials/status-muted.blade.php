@if (in_array($selecao->estado, ['Em Elaboração', 'Aguardando Início']))
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Em Andamento')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Encerrada')
  <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@endif
