@nomenclatura

Olá {{ $candidatonome }},<br />
<br />
O período de {{ $inscricao_ou_matricula_plural }} {{ $objetivo == 'aluno especial' ? 'para ' . $objetivo : 'd' . $objetivo }} encerra-se em {{ formatarDataHora($selecao->inscricoes_datahora_fim) }}.<br />
Você iniciou sua {{ $inscricao_ou_matricula }}, mas não a enviou.<br />
Entre em sua {{ $inscricao_ou_matricula }}, envie todos os documentos exigidos e clique no botão "Enviar {{ ucfirst($inscricao_ou_matricula) }}".<br />
Sem isso, ela não será avaliada!<br />
<br />
@include('emails.rodape')
