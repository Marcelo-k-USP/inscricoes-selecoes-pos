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
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Aguardando Comprovação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Comprovação">
    Aguardando Comprovação
  </button>
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Solicitada') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Isenção de Taxa Solicitada">
    Isenção de Taxa Solicitada
  </button>
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa em Avaliação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Isenção de Taxa em Avaliação">
  Isenção de Taxa em Avaliação
  </button>
  @if ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Aprovada')
    <button class="btn btn-sm btn-success" disabled name="estado" value="Isenção de Taxa Aprovada">
      Isenção de Taxa Aprovada
    </button>
  @endif
  @if ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Rejeitada')
    <button class="btn btn-sm btn-danger" disabled name="estado" value="Isenção de Taxa Rejeitada">
      Isenção de Taxa Rejeitada
    </button>
  @endif
</div>
