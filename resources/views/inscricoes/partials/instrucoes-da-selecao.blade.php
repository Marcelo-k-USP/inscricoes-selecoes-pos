<div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
  @if ($inscricao->selecao->settings()->get('instrucoes'))
    {!! nl2br(linkify($inscricao->selecao->settings()->get('instrucoes'))) !!}
    <br />
  @endif
  As inscrições para este processo seletivo vão de {{ formatarData($inscricao->selecao->data_inicio) }} até {{ formatarData($inscricao->selecao->data_fim) }}<br />
  Você {{ (!$solicitacaoisencaotaxa) ? 'não ' : '' }}está isento de pagar a taxa de inscrição para esta seleção
  <button type="button" class="close" data-toggle="collapse" data-target="#instrucoes">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
