<div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
  @if ($inscricao->selecao->settings()->get('instrucoes'))
    {!! nl2br(linkify($inscricao->selecao->settings()->get('instrucoes'))) !!}
    <br />
  @endif
  As inscrições para este processo seletivo vão de {{ formatarDataHora($inscricao->selecao->inscricoes_datahora_inicio) }} até {{ formatarDataHora($inscricao->selecao->inscricoes_datahora_fim) }}.<br />
  @if ($inscricao->selecao->tem_taxa)
    Você {{ $solicitacaoisencaotaxa_aprovada ? '' : 'não ' }}está isento de pagar a taxa de inscrição para esta seleção.
  @else
    Não há taxa de inscrição para esta seleção.
  @endif
  <br />
  Após informar seus dados, clique em "Prosseguir", envie todos os documentos exigidos e clique no botão "Enviar Inscrição".
  Sem isso, sua inscrição não será avaliada!<br />
  Caso queira renomear ou apagar um documento, passe o mouse sobre o nome dele (no celular, toque no nome dele) e clique/toque nos botões que aparecerão.<br />
  <button type="button" class="close" data-toggle="collapse" data-target="#instrucoes">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
