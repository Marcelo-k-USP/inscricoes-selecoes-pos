Olá {{ $user->name }},<br />
<br />
Seu boleto para o processo seletivo {{ $inscricao->selecao->nome }} está em anexo.<br />
Não deixe de pagá-lo.<br />
<br />
@include('emails.rodape')
