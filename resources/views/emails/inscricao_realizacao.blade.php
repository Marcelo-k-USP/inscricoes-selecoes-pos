{{ $responsavel_nome }},
<br />
Foi enviada uma inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }}.<br />
Favor avaliar os documentos do candidato, e pré-aprovar (ou pré-reprovar) sua inscrição no sistema.<br />
<br />
@include('emails.rodape')
