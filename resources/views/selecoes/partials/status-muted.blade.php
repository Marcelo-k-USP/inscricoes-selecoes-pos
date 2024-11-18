@if ($selecao->estado == 'Emcerrada')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Em elaboração')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@elseif ($selecao->estado == 'Em andamento')
    <span class="badge badge-light text-secondary"> {{ $selecao->estado }} </span>
@endif
