Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua inscrição para o processo seletivo {{ $inscricao->selecao->nome }} foi rejeitada.<br />
<br />
{{ $inscricao->selecao->email_inscricaorejeicao_texto }}
<br />
@include('emails.rodape')
