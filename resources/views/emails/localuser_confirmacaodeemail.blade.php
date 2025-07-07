Olá {{ $localuser->name }},<br />
<br />
Você se cadastrou no sistema de inscrições para seleções da pós-graduação.<br />
Clique neste link para confirmar seu e-mail: {{ $email_confirmation_url }}<br />
Em seguida, faça login e solicite isenção de taxa ou se inscreva em nossos processos seletivos.<br />
<br />
Salientamos que é de responsabilidade do usuário acompanhar o andamento do processo no sistema, desde a validade de seu cadastro básico, até sua(s) eventual(is) inscrição(ões) e resultado da(s) mesma(s).
<br />
@include('emails.rodape')
