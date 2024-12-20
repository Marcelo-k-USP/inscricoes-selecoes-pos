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
  <button class="btn btn-sm {{ ($selecao->estado == 'Aguardando Documentação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Documentação">
    Aguardando Documentação
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Aguardando Início') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Início">
    Aguardando Início
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Em Andamento') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Em Andamento">
    Em Andamento
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Encerrada') ? 'btn-danger' : 'btn-secondary' }}" disabled name="estado" value="Encerrada">
    Encerrada
  </button>
</div>
