@nomenclatura(['selecao' => $solicitacaoisencaotaxa->selecao])

{{ $servicoposgraduacao_nome }},
<br />
Foi solicitada uma isenção de taxa para {{ $objetivo }}.<br />
Clique <a href="{{ config('app.url') }}/solicitacoesisencaotaxa/edit/{{ $solicitacaoisencaotaxa->id }}">aqui</a> para avaliar os dados e documentos do candidato, e aprovar (ou reprovar) sua solicitação de isenção de taxa no sistema.<br />
<br />
@include('emails.rodape')
