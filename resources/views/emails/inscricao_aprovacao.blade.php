Olá {{ $user->name }},<br />
<br />
Sua inscrição para o processo seletivo {{ $inscricao->selecao->nome }} foi aceita.<br />
<br />
{{ $inscricao->selecao->email_inscricaoaprovacao_texto }}
<br />
@include('emails.rodape')
