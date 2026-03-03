@nomenclatura(['selecao' => $solicitacaoisencaotaxa->selecao])

Olá {{ $user->name }},<br />
<br />
Sua solicitação de isenção de taxa para {{ $objetivo }} foi aceita.<br />
Prossiga realizando sua {{ $inscricao_ou_matricula }} no período determinado.<br />
<br />
@include('emails.rodape')
