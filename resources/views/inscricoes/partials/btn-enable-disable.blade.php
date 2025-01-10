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

{{ html()->form('post', 'inscricoes/edit/' . $inscricao->id)->open() }}
  @method('put')
  @csrf
  <input type="hidden" name="conjunto_alterado" id="conjunto_alterado" value="estado">
  <div class="btn-group btn-enable-disable">
    <button type="submit" class="btn btn-sm {{ ($inscricao->estado == 'Aguardando Documentação') ? 'btn-warning' : 'btn-secondary' }}" disabled name="estado" value="Aguardando Documentação">
      Aguardando Documentação
    </button>
    <button type="submit" class="btn btn-sm {{ ($inscricao->estado == 'Realizada') ? 'btn-success' : 'btn-secondary' }}" disabled name="estado" value="Realizada">
      Realizada
    </button>
    <button type="submit" class="btn btn-sm {{ ($inscricao->estado == 'Em Avaliação') ? 'btn-warning' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($inscricao->estado != 'Realizada')) disabled @endif name="estado" value="Em Avaliação">
      Em Avaliação
    </button>
    @if (in_array($inscricao->estado, ['Em Avaliação', 'Aprovada']))
      <button type="submit" class="btn btn-sm {{ ($inscricao->estado == 'Aprovada') ? 'btn-success' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($inscricao->estado != 'Em Avaliação')) disabled @endif name="estado" value="Aprovada">
        Aprovada
      </button>
    @endif
    @if (in_array($inscricao->estado, ['Em Avaliação', 'Rejeitada']))
      <button type="submit" class="btn btn-sm {{ ($inscricao->estado == 'Rejeitada') ? 'btn-danger' : 'btn-secondary' }}" @if ((session('perfil') == 'usuario') || ($inscricao->estado != 'Em Avaliação')) disabled @endif name="estado" value="Rejeitada">
        Rejeitada
      </button>
    @endif
  </div>
{{ html()->form()->close() }}
