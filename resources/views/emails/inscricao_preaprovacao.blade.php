Olá {{ $user->name }},<br />
<br />
Sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }} teve os dados e documentos analisados.<br />
Para acompanhar o estado da sua inscrição, clique <a href="{{ $link_acompanhamento }}">aqui</a>.<br />
<br />
@include('emails.rodape')
