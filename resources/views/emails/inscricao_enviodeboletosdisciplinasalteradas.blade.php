@nomenclatura(['selecao' => $inscricao->selecao])

Olá {{ $user->name }},<br />
<br />
Você reenviou sua matrícula para {{ $objetivo }}.<br />
Pelo fato de você ter incluído e/ou removido disciplinas, o sistema gerou novo(s) boleto(s) para pagamento.<br />
Não deixe de pagar {{ ($arquivos_count == 1) ? 'o boleto que segue' : 'os boletos que seguem' }} em anexo.<br />
<br />
@foreach ($arquivos_erro as $arquivo_erro)
  {!! $arquivo_erro !!}<br />
@endforeach
@include('emails.rodape')
