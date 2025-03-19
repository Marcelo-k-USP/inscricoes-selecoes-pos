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
  <button class="btn btn-sm {{ ($selecao->estado == 'Em Elaboração') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Em Elaboração">
    Em Elaboração
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Aguardando Início') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Início">
    Aguardando Início
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Período de Solicitações de Isenção') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Período de Solicitações de Isenção">
    Período de Solicitações de Isenção
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Período de Inscrições') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Período de Inscrições">
    Período de Inscrições
  </button>
  <button class="btn btn-sm {{ ($selecao->estado == 'Encerrada') ? 'btn-danger' : 'btn-secondary' }}" disabled name="estado" value="Encerrada">
    Encerrada
  </button>
</div>
