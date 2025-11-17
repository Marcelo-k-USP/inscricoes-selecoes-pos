Olá {{ $user->name }},<br />
<br />
Lamentamos, mas sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }} foi rejeitada.<br />
<br />
{{ $inscricao->selecao->email_inscricaorejeicao_texto }}
<br />
@include('emails.rodape')
