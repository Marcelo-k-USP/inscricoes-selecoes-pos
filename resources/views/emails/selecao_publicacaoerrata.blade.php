@nomenclatura

Olá {{ $candidatonome }},<br />
<br />
Foi publicada uma errata {{ $objetivo == 'aluno especial' ? 'para ' . $objetivo : 'n' . $objetivo }}.<br />
Recomendamos que você a consulte, para não perder nenhuma informação importante.<br />
Para tal, entre em sua {{ $inscricao_ou_matricula }} (ou solicitação de isenção de taxa) e consulte o quadro "Informativos".<br />
<br />
@include('emails.rodape')
