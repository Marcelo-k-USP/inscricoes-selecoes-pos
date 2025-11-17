Olá {{ $user->name }},<br />
<br />
Sua inscrição para {{ ($inscricao->selecao->categoria->nome != 'Aluno Especial') ? 'o processo seletivo ' . $inscricao->selecao->nome : 'aluno especial' }} está pendente de envio.<br />
Para prosseguir com sua inscrição, clique <a href="{{ config('app.url') }}/inscricoes/edit/{{ $inscricao->id }}#card_arquivos">aqui</a> e envie os documentos necessários.<br />
Tendo enviado todos os documentos, clique no botão "Enviar Inscrição" que fica abaixo da lista de documentos.<br />
<b>Sem isso, sua inscrição não será avaliada!</b><br />
<br />
@include('emails.rodape')
