@if ($row->estado == 'Em andamento')
    <span class="badge badge-success"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Em elaboração')
    <span class="badge badge-warning"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Encerrada')
    <span class="badge badge-danger"> {{ $row->estado }} </span>
@else
@endif
