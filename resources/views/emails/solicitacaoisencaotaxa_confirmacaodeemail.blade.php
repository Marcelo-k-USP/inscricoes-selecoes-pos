Olá {{ $localuser->name }},<br />
<br />
Você iniciou sua solicitação de isenção de taxa no processo seletivo {{ $solicitacaoisencaotaxa->selecao->nome }}.<br />
Clique neste link para confirmar seu e-mail: {{ $email_confirmation_url }}<br />
Em seguida, faça login e acesse sua solicitação para enviar todos os arquivos requeridos.<br />
<br />
@include('emails.rodape')
