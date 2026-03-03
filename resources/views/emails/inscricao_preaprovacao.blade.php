@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Sua {{ $inscricao_ou_matricula }} para {{ $objetivo }} teve os dados e documentos analisados.<br />
Para acompanhar o estado da sua {{ $inscricao_ou_matricula }}, clique <a href="{{ $link_acompanhamento }}">aqui</a>.<br />
<br />
@include('emails.rodape')
