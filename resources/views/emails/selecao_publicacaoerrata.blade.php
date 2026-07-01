@nomenclatura

Olá {{ $candidatonome }},<br />
<br />
Foi publicada uma errata {{ $objetivo == 'aluno especial' ? 'para ' . $objetivo : 'n' . $objetivo }}.<br />
Recomendamos que você a consulte, para não perder nenhuma informação importante.<br />
Para tal, entre em sua solicitação de isenção de taxa, inscrição ou matrícula e consulte o quadro "Informativos".<br />
<br />
@include('emails.rodape')
