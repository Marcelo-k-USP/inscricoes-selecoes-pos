@section('styles')
@parent
{{-- https://stackoverflow.com/questions/50349017/how-can-i-change-cursor-for-disabled-button-or-a-in-bootstrap-4 --}}
<style>
    button:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
</style>
@endsection

<div class="btn-group btn-enable-disable">
    <button class="btn btn-sm {{ ($selecao->estado == 'Em elaboração') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Em elaboração">
        Em elaboração
    </button>
    <button class="btn btn-sm {{ ($selecao->estado == 'Em andamento') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Em andamento">
        Em andamento
    </button>
    <button class="btn btn-sm {{ ($selecao->estado == 'Encerrada') ? 'btn-danger' : 'btn-secondary' }}" disabled name="estado" value="Encerrada">
        Encerrada
    </button>
</div>
