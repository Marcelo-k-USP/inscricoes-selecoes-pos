Olá {{ $user->name }},<br />
<br />
Você iniciou sua inscrição no processo seletivo {{ $inscricao->selecao->nome }}.<br />
Clique neste link para confirmar seu e-mail: {{ $email_confirmation_url }}<br />
Em seguida, faça login e acesse sua inscrição para enviar todos os arquivos requeridos.<br />
<br />
@include('emails.rodape')
