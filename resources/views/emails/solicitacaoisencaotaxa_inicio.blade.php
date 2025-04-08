Olá {{ $user->name }},<br />
<br />
Sua solicitação de isenção de taxa para o processo seletivo {{ $solicitacaoisencaotaxa->selecao->nome }} está pendente de envio.<br />
Para prosseguir com sua solicitação, clique <a href="{{ config('app.url') }}/solicitacoesisencaotaxa/edit/{{ $solicitacaoisencaotaxa->id }}#card_arquivos">aqui</a> e envie os documentos necessários.<br />
Tendo enviado todos os documentos, clique no botão "Enviar Solicitação" que fica abaixo da lista de documentos.<br />
<b>Sem isso, sua solicitação não será avaliada!</b><br />
<br />
@include('emails.rodape')
