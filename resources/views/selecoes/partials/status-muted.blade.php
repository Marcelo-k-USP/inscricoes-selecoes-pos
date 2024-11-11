@if ($selecao->estado == 'Desativada')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Em elaboração')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Em produção')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@endif
