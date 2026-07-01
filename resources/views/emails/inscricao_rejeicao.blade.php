@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua inscrição para {{ $objetivo }} foi rejeitada.<br />
<br />
{{ $inscricao->selecao->email_inscricaomatricularejeicao_texto }}
<br />
@include('emails.rodape')
