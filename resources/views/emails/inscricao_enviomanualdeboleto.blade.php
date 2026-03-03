@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Seu boleto para {{ $objetivo }} está em anexo.<br />
Não deixe de pagá-lo.<br />
<br />
@include('emails.rodape')
