<table class="table table-bordered table-sm text-center">
  <tr>
    <th>Ano</th>
    <th>Tot.</th>
    <th>jan</th>
    <th>fev</th>
    <th>mar</th>
    <th>abr</th>
    <th>mai</th>
    <th>jun</th>
    <th>jul</th>
    <th>ago</th>
    <th>set</th>
    <th>out</th>
    <th>nov</th>
    <th>dez</th>
  </tr>
  @foreach ($selecao->contarSolicitacoesIsencaoTaxaPorAno() as $anual)
    <tr>
      <th>{{ $anual->ano }}</th>
      <th>
        {{ $anual->count }}
        <a href="{{ route('selecoes.downloadsolicitacoesisencaotaxa', $selecao) }}?ano={{ $anual->ano }}" title="Fazer download das solicitações"><i class="fas fa-download"></i></a>
      </th>
      @foreach ($selecao->contarSolicitacoesIsencaoTaxaPorMes($anual->ano) as $mes)
        <td>{{ $mes }}</td>
      @endforeach
    </tr>
  @endforeach
</table>
