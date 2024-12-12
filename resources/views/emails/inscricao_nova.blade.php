Olá {{ $autor->name }},<br />
<br />
Você realizou sua inscrição com sucesso no processo seletivo {{ $inscricao->selecao->nome }}.<br />
<br />
{!! $arquivo_erro !!}<br />
@include('emails.rodape')
