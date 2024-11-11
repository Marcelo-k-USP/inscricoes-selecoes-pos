@if ($inscricao->selecao->settings()->get('instrucoes'))
  <div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
    {!! nl2br(linkify($inscricao->selecao->settings()->get('instrucoes'))) !!}
    <button type="button" class="close"  data-toggle="collapse" data-target="#instrucoes">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif
