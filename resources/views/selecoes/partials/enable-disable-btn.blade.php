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

{{ html()->form('post', url('selecoes/' . $selecao->id))->attribute('name', 'form_estado')->open() }}
    @method('put')
    @csrf
    <div class="btn-group enable-disable-btn">
        <button type="submit" class="btn btn-sm {{($selecao->estado == 'Em elaboração') ? 'btn-warning' : 'btn-secondary'}}" {{($selecao->estado == 'Em elaboração') ? 'disabled' : ''}} name="estado" value="Em elaboração">
            Em elaboração
        </button>
        <button type="submit" class="btn btn-sm {{($selecao->estado == 'Em produção') ? 'btn-success' : 'btn-secondary'}}" {{($selecao->estado == 'Em produção') ? 'disabled' : ''}} name="estado" value="Em produção">
            Em produção
        </button>
        <button type="submit" class="btn btn-sm {{($selecao->estado == 'Desativada') ? 'btn-danger' : 'btn-secondary'}}" {{($selecao->estado == 'Desativada') ? 'disabled' : ''}} name="estado" value="Desativada">
            Desativada
        </button>
    </div>
{{ html()->form()->close() }}
