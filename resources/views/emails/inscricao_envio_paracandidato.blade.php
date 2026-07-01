@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Você completou sua inscrição para {{ $objetivo }}.<br />
@if (($boleto_momento_envio == 'Envio da Inscrição/Matrícula') && ($arquivos_count > 0))
  Não deixe de pagar {{ ($arquivos_count == 1) ? 'o boleto que segue' : 'os boletos que seguem' }} em anexo.<br />
@endif
<br />
@foreach ($arquivos_erro as $arquivo_erro)
  {!! $arquivo_erro !!}<br />
@endforeach
@include('emails.rodape')
