@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua {{ $inscricao_ou_matricula }} para {{ $objetivo }} foi rejeitada devido a problema(s) no(s) dado(s) e/ou documento(s) fornecido(s).<br />
<br />
@include('emails.rodape')
