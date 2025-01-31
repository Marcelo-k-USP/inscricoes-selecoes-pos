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

{{ html()->form('post', 'solicitacoesisencaotaxa/edit/' . $solicitacaoisencaotaxa->id)->open() }}
  @method('put')
  @csrf
  <input type="hidden" name="conjunto_alterado" id="conjunto_alterado" value="estado">
  <div class="btn-group btn-enable-disable">
    <button type="submit" class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Aguardando Envio') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Envio">
      Aguardando Envio
    </button>
    @if ($solicitacaoisencaotaxa->estado != 'Aguardando Envio')
      <button type="submit" class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Solicitada') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Isenção de Taxa Solicitada">
        Isenção de Taxa Solicitada
      </button>
    @endif
    @if ($solicitacaoisencaotaxa->estado != 'Aguardando Envio')
      <button type="submit" class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa em Avaliação') ? 'btn-warning' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($solicitacaoisencaotaxa->estado != 'Isenção de Taxa Solicitada')) disabled @endif name="estado" value="Isenção de Taxa em Avaliação">
        Isenção de Taxa em Avaliação
      </button>
    @endif
    @if (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa em Avaliação', 'Isenção de Taxa Aprovada']))
      <button type="submit" class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Aprovada') ? 'btn-success' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($solicitacaoisencaotaxa->estado != 'Isenção de Taxa em Avaliação')) disabled @endif name="estado" value="Isenção de Taxa Aprovada">
        Isenção de Taxa Aprovada
      </button>
    @endif
    @if (in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa em Avaliação', 'Isenção de Taxa Rejeitada']))
      <button type="submit" class="btn btn-sm {{ ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Rejeitada') ? 'btn-danger' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($solicitacaoisencaotaxa->estado != 'Isenção de Taxa em Avaliação')) disabled @endif name="estado" value="Isenção de Taxa Rejeitada">
        Isenção de Taxa Rejeitada
      </button>
    @endif
  </div>
{{ html()->form()->close() }}
