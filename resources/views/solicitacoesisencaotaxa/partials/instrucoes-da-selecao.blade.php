<div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
  @if ($solicitacaoisencaotaxa->selecao->settings()->get('instrucoes'))
    {!! nl2br(linkify($solicitacaoisencaotaxa->selecao->settings()->get('instrucoes'))) !!}
    <br />
  @endif
  As inscrições para este processo seletivo vão de {{ formatarData($solicitacaoisencaotaxa->selecao->data_inicio) }} até {{ formatarData($solicitacaoisencaotaxa->selecao->data_fim) }}
  <button type="button" class="close" data-toggle="collapse" data-target="#instrucoes">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
