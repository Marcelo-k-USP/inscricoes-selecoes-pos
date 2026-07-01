@nomenclatura(['selecao' => $inscricao->selecao])

{{ $responsavel_nome }},
<br />
Foi enviada uma inscrição para {{ $objetivo }}.<br />
Clique <a href="{{ config('app.url') }}/inscricoes/edit/{{ $inscricao->id }}">aqui</a> para avaliar os dados e documentos do candidato, e pré-aprovar (ou pré-rejeitar) sua inscrição no sistema.<br />
<br />
@include('emails.rodape')
