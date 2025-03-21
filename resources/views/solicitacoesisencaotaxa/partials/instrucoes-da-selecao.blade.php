<div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
  @if ($solicitacaoisencaotaxa->selecao->settings()->get('instrucoes'))
    {!! nl2br(linkify($solicitacaoisencaotaxa->selecao->settings()->get('instrucoes'))) !!}
    <br />
  @endif
  As solicitações de isenção para este processo seletivo vão de {{ formatarDataHora($solicitacaoisencaotaxa->selecao->solicitacoesisencaotaxa_datahora_inicio) }} até {{ formatarDataHora($solicitacaoisencaotaxa->selecao->solicitacoesisencaotaxa_datahora_fim) }}
  <button type="button" class="close" data-toggle="collapse" data-target="#instrucoes">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
