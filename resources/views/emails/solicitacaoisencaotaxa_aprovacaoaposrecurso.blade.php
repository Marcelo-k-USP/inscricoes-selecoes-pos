Olá {{ $user->name }},<br />
<br />
Seu recurso referente à isenção de taxa para o processo seletivo {{ $solicitacaoisencaotaxa->selecao->nome }} foi deferido.<br />
Prossiga realizando sua inscrição no período determinado.<br />
<br />
@include('emails.rodape')
