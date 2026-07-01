@nomenclatura(['selecao' => $matricula->selecao])

<div class="alert alert-primary collapse {{ empty($hide) ? 'show' : '' }}" role="alert" id="instrucoes">
  @if ($matricula->selecao->settings()->get('instrucoes'))
    {!! nl2br(linkify($matricula->selecao->settings()->get('instrucoes'))) !!}
    <br />
  @endif
  As matrículas para {{ $objetivo }} vão de {{ formatarDataHora($matricula->selecao->inscricoesmatriculas_datahora_inicio) }} até {{ formatarDataHora($matricula->selecao->inscricoesmatriculas_datahora_fim) }}.<br />
  @if ($matricula->selecao->tem_taxa)
    Você {{ $solicitacaoisencaotaxa_aprovada ? '' : 'não ' }}está isento de pagar a taxa de matrícula para esta seleção.
  @else
    Não há taxa de matrícula para esta seleção.
  @endif
  <br />
  Após informar seus dados, clique em "Prosseguir", envie todos os documentos exigidos e clique no botão "Enviar Matrícula".
  Sem isso, ela não será avaliada!<br />
  Caso queira renomear ou apagar um documento, passe o mouse sobre o nome dele (no celular, toque no nome dele) e clique/toque nos botões que aparecerão.<br />
  <button type="button" class="close" data-toggle="collapse" data-target="#instrucoes">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
