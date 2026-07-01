@nomenclatura(['selecao' => $matricula->selecao])

Olá {{ $user->name }},<br />
<br />
Sua matrícula para {{ $objetivo }} teve os dados e documentos analisados.<br />
@if (!empty($link_acompanhamento))
  Para acompanhar o estado da sua matrícula, clique <a href="{{ $link_acompanhamento }}">aqui</a>.<br />
@endif
<br />
@include('emails.rodape')
