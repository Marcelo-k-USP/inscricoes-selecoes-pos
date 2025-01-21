Olá {{ $localuser->name }},<br />
<br />
Você se cadastrou no sistema de inscrições para seleções da pós-graduação.<br />
Clique neste link para confirmar seu e-mail: {{ $email_confirmation_url }}<br />
Em seguida, faça login e solicite isenção de taxa ou se inscreva em nossos processos seletivos.<br />
<br />
@include('emails.rodape')
