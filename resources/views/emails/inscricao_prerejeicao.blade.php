Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }} foi rejeitada devido a problema(s) no(s) dado(s) e/ou documento(s) fornecido(s).<br />
<br />
@include('emails.rodape')
