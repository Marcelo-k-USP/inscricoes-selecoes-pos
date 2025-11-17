Olá {{ $user->name }},<br />
<br />
Sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }} foi aceita.<br />
<br />
{{ $inscricao->selecao->email_inscricaoaprovacao_texto }}
<br />
@include('emails.rodape')
