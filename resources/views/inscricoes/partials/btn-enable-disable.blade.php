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
  <button class="btn btn-sm {{ ($inscricao->estado == 'Aguardando Documentação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Documentação">
    Aguardando Documentação
  </button>
  <button class="btn btn-sm {{ ($inscricao->estado == 'Realizada') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Realizada">
    Realizada
  </button>
  <button class="btn btn-sm {{ ($inscricao->estado == 'Em Avaliação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Em Avaliação">
    Em Avaliação
  </button>
  @if ($inscricao->estado == 'Aceita')
    <button class="btn btn-sm btn-success" disabled name="estado" value="Aceita">
      Aceita
    </button>
  @endif
  @if ($inscricao->estado == 'Rejeitada')
    <button class="btn btn-sm btn-danger" disabled name="estado" value="Rejeitada">
      Rejeitada
    </button>
  @endif
  @if ($inscricao->estado == 'Pendente')
    <button class="btn btn-sm btn-warning" disabled name="estado" value="Pendente">
      Pendente
    </button>
  @endif
  @if ($inscricao->estado == 'Cancelada')
    <button class="btn btn-sm btn-danger" disabled name="estado" value="Cancelada">
      Cancelada
    </button>
  @endif
  @if ($inscricao->estado == 'Concluída')
    <button class="btn btn-sm btn-success" disabled name="estado" value="Concluída">
      Concluída
    </button>
  @endif
</div>
