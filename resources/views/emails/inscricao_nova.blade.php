Olá {{ $autor->name }},<br />
<br />
Você realizou sua inscrição com sucesso no processo seletivo {{ $inscricao->selecao->nome }}.<br />
Não deixe de pagar o boleto em anexo, e também não deixe de enviar todos os arquivos requeridos na inscrição.<br />
<br />
{!! $arquivo_erro !!}<br />
@include('emails.rodape')
