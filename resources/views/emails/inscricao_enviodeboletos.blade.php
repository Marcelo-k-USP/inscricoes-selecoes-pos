@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Você completou sua {{ $inscricao_ou_matricula }} para {{ $objetivo }}.<br />
Não deixe de pagar {{ ($arquivos_count == 1) ? 'o boleto que segue' : 'os boletos que seguem' }} em anexo.<br />
<br />
@foreach ($arquivos_erro as $arquivo_erro)
  {!! $arquivo_erro !!}<br />
@endforeach
@include('emails.rodape')
