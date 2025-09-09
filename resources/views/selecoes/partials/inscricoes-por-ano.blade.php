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
  @foreach ($selecao->contarInscricoesPorAno() as $anual)
    <tr>
      <th>{{ $anual->ano }}</th>
      <th>
        {{ $anual->count }}
        <a href="{{ route('selecoes.downloadinscricoes', $selecao) }}?ano={{ $anual->ano }}" title="Fazer download dos dados das inscrições"><i class="fas fa-download"></i></a>
        <a href="javascript:void(0);" onclick="baixar_todos_arquivos('arquivos/ziptodosdosobjetosdaselecao/Inscricao/{{ $selecao->id }}', 'arquivos/downloadtodosdosobjetosdaselecao/Inscricao/{{ $selecao->id }}', true)" title="Fazer download dos documentos das inscrições"><i class="fas fa-download" style="color: #CD5C5C;"></i></a>
      </th>
      @foreach ($selecao->contarInscricoesPorMes($anual->ano) as $mes)
        <td>{{ $mes }}</td>
      @endforeach
    </tr>
  @endforeach
</table>
