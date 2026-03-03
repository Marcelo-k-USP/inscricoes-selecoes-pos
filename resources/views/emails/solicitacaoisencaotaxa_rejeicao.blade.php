@nomenclatura(['selecao' => $solicitacaoisencaotaxa->selecao])

Olá {{ $user->name }},<br />
<br />
Sua solicitação de isenção de taxa para {{ $objetivo }} foi rejeitada.<br />
Mesmo assim, você pode prosseguir realizando sua {{ $inscricao_ou_matricula }} e, em seguida, pagando a taxa.<br />
<br />
@include('emails.rodape')
