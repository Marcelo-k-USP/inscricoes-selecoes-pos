Olá {{ $localuser->name }},<br />
<br />
Você se cadastrou no sistema de inscrições para seleções da pós-graduação.<br />
Clique neste link para confirmar seu e-mail: {{ $email_confirmation_url }}<br />
Em seguida, faça login e prossiga solicitando isenções de taxa ou efetuando inscrições ou matrículas.<br />
<br />
Salientamos que é de responsabilidade do usuário acompanhar o andamento do processo no sistema, desde a validade de seu cadastro básico, até sua(s) eventual(is) inscrição(ões) e resultado da(s) mesma(s).
<br />
@include('emails.rodape')
