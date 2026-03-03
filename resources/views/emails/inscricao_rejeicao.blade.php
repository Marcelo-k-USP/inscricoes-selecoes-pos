@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua {{ $inscricao_ou_matricula }} para {{ $objetivo }} foi rejeitada.<br />
<br />
{{ $inscricao->selecao->email_inscricaorejeicao_texto }}
<br />
@include('emails.rodape')
