@if ($selecao->estado == 'Em andamento')
    <span class="text-success" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Desativada')
    <span class="text-danger" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($selecao->estado == 'Em elaboração')
    <span class="text-warning" data-toggle="tooltip" title="{{ $selecao->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
