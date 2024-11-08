@section('styles')
@parent
<style>
    #card-fila-principal {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }

</style>
@endsection

<div class="card mb-3" id="card-fila-principal">
    <div class="card-header">
        Informações básicas
        &nbsp; | &nbsp;
        @include('common.list-table-btn-edit', ['row'=>$selecao])
    </div>
    <div class="card-body">
        <span class="text-muted">Processo:</span> {{ $selecao->processo->nome }}<br>
        <span class="text-muted">Nome:</span> {{ $selecao->nome }}<br>
        <span class="text-muted">Descrição:</span> {{ $selecao->descricao }}<br>
    </div>
</div>
