@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Sua {{ $inscricao_ou_matricula }} para {{ $objetivo }} foi aceita.<br />
<br />
{{ $inscricao->selecao->email_inscricaoaprovacao_texto }}
<br />
@include('emails.rodape')
