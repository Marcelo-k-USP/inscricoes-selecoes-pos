@nomenclatura(['selecao' => $solicitacaoisencaotaxa->selecao])

Olá {{ $user->name }},<br />
<br />
Seu recurso referente à isenção de taxa para {{ $objetivo }} foi deferido.<br />
Prossiga realizando sua {{ $inscricao_ou_matricula }} no período determinado.<br />
<br />
@include('emails.rodape')
