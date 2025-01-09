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
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa_ou_inscricao->estado == 'Aguardando Documentação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Documentação">
    Aguardando Documentação
  </button>
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa_ou_inscricao->estado == 'Realizada') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Realizada">
    Realizada
  </button>
  <button class="btn btn-sm {{ ($solicitacaoisencaotaxa_ou_inscricao->estado == 'Em Avaliação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Em Avaliação">
    Em Avaliação
  </button>
  @if ($solicitacaoisencaotaxa_ou_inscricao->estado == 'Aprovada')
    <button class="btn btn-sm btn-success" disabled name="estado" value="Aprovada">
      Aprovada
    </button>
  @endif
  @if ($solicitacaoisencaotaxa_ou_inscricao->estado == 'Rejeitada')
    <button class="btn btn-sm btn-danger" disabled name="estado" value="Rejeitada">
      Rejeitada
    </button>
  @endif
</div>
