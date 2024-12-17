Olá {{ $autor->name }},<br />
<br />
Você iniciou sua inscrição no processo seletivo {{ $inscricao->selecao->nome }}.<br />
Não deixe de enviar todos os arquivos requeridos na inscrição, e também não deixe de pagar o boleto que segue em anexo.<br />
<br />
{!! $arquivo_erro !!}<br />
@include('emails.rodape')
