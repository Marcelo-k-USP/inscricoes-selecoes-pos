Olá {{ $user->name }},<br />
<br />
Você completou sua inscrição no processo seletivo {{ $inscricao->selecao->nome }}.<br />
Não deixe de pagar o boleto que segue em anexo.<br />
<br />
{!! $arquivo_erro !!}<br />
@include('emails.rodape')
