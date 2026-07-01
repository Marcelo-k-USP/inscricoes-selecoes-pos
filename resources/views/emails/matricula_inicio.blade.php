@nomenclatura(['selecao' => $matricula->selecao])

Olá {{ $user->name }},<br />
<br />
Sua matrícula para {{ $objetivo }} está pendente de envio.<br />
Para prosseguir com sua matrícula, clique <a href="{{ config('app.url') }}/matriculas/edit/{{ $matricula->id }}#card_arquivos">aqui</a> e envie os documentos necessários.<br />
Tendo enviado todos os documentos, clique no botão "Enviar Matrícula" que fica abaixo da lista de documentos.<br />
<b>Sem isso, ela não será avaliada!</b><br />
<br />
@include('emails.rodape')
