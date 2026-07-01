@nomenclatura(['selecao' => $matricula->selecao])

{{ $responsavel_nome }},
<br />
Foi enviada uma matrícula para {{ $objetivo }}.<br />
Clique <a href="{{ config('app.url') }}/matriculas/edit/{{ $matricula->id }}">aqui</a> para avaliar os dados e documentos do candidato, e pré-aprovar (ou pré-rejeitar) sua matrícula no sistema.<br />
<br />
@include('emails.rodape')
