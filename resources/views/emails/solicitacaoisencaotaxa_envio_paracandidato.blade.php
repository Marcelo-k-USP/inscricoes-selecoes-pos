@nomenclatura(['selecao' => $solicitacaoisencaotaxa->selecao])

Olá {{ $user->name }},<br />
<br />
Você completou sua solicitação de isenção de taxa para {{ $objetivo }}.<br />
<br />
@include('emails.rodape')
