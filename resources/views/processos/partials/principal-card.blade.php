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
        @include('common.list-table-btn-edit', ['row'=>$processo])
    </div>
    <div class="card-body">
        <span class="text-muted">Nome:</span> {{ $processo->nome }}<br>
        <span class="text-muted">Descrição:</span> {{ $processo->descricao }}<br>
    </div>
</div>
