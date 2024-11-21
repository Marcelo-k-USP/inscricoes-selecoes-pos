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

{{ html()->form('post', url('selecoes/edit-status/' . $selecao->id))->attribute('name', 'form_estado')->open() }}
    @csrf
    @method('put')
    <div class="btn-group enable-disable-btn">
        <button type="submit" class="btn btn-sm {{ ($selecao->estado == 'Em elaboração') ? 'btn-warning' : 'btn-secondary' }}" {{ ($selecao->estado == 'Em elaboração') ? 'disabled' : '' }} name="estado" value="Em elaboração">
            Em elaboração
        </button>
        <button type="submit" class="btn btn-sm {{ ($selecao->estado == 'Em andamento') ? 'btn-success' : 'btn-secondary' }}" {{ ($selecao->estado == 'Em andamento') ? 'disabled' : '' }} name="estado" value="Em andamento">
            Em andamento
        </button>
        <button type="submit" class="btn btn-sm {{ ($selecao->estado == 'Encerrada') ? 'btn-danger' : 'btn-secondary' }}" {{ ($selecao->estado == 'Encerrada') ? 'disabled' : '' }} name="estado" value="Encerrada">
            Encerrada
        </button>
    </div>
{{ html()->form()->close() }}
