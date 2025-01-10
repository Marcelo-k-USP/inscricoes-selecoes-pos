Olá {{ $user->name }},<br />
<br />
Sua solicitação de isenção de taxa para o processo seletivo {{ $solicitacaoisencaotaxa->selecao->nome }} foi rejeitada.<br />
Mesmo assim, você pode prosseguir realizando sua inscrição e, em seguida, pagando a taxa.<br />
<br />
@include('emails.rodape')
