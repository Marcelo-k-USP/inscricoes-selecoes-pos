@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Sua inscrição para {{ $objetivo }} teve os dados e documentos analisados.<br />
@if (!empty($link_acompanhamento))
  Para acompanhar o estado da sua inscrição, clique <a href="{{ $link_acompanhamento }}">aqui</a>.<br />
@endif
<br />
@include('emails.rodape')
