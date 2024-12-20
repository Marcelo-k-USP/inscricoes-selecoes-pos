@if (in_array($row->estado, ['Aguardando Documentação', 'Aguardando Início']))
  <span class="badge badge-warning"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Em Andamento')
  <span class="badge badge-success"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Encerrada')
  <span class="badge badge-danger"> {{ $row->estado }} </span>
@endif
